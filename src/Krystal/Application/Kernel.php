<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application;

use Krystal\Debug\Exception\Handler as ExceptionHandler;
use Krystal\InstanceManager\ServiceLocator;
use Krystal\InstanceManager\DependencyInjectionContainer;
use Krystal\Application\Route\Router;
use Krystal\Application\Route\RouteNotation;
use Krystal\Application\InputInterface;
use Krystal\Application\Component;
use Throwable;
use RuntimeException;
use LogicException;

/**
 * This class abstracts application initialization logic
 * And parses configuration array
 */
final class Kernel implements KernelInterface
{
    /**
     * Configuration container
     * 
     * @var array
     */
    private $config = array();

    /**
     * Environment container
     * 
     * @var \Krystal\Application\Input
     */
    private $input;

    const DEFAULT_CHARSET = 'UTF-8';
    const ERR_LEVEL_MAX = -1;
    const ERR_LEVEL_NONE = 0;

    /**
     * State initialization
     * 
     * @param \Krystal\Application\InputInterface $input
     * @param array $config
     * @return void
     */
    public function __construct(InputInterface $input, array $config)
    {
        $this->input = $input;
        $this->config = $config;
    }

    /**
     * Returns prepared and configured framework components
     * 
     * @return array
     */
    private function getComponents()
    {
        // Order of components being registered is extremely important!
        return array(
            new Component\Request(),
            new Component\Paginator(),
            new Component\Db(),
            new Component\MapperFactory(),
            new Component\SessionBag(),
            new Component\AuthManager(),
            new Component\AuthAttemptLimit(),
            new Component\ParamBag(),
            new Component\AppConfig(),
            new Component\Config(),
            new Component\ModuleManager(),
            new Component\Translator(),
            new Component\Response(),
            new Component\FlashBag(),
            new Component\FormAttribute(),
            new Component\WidgetFactory(),
            new Component\UrlBuilder(),
            new Component\View(),
            new Component\Profiler(),
            new Component\Cache(),
            new Component\CsrfProtector(),
            new Component\Captcha(),
            new Component\Logger(),
            // Middleware must be registered before Dispatcher
            new Component\Middleware(),
            // Dispatcher always must be very last component to be registered
            new Component\Dispatcher()
        );
    }

    /**
     * Returns configured and prepared core services
     * 
     * @return array
     */
    private function getServices()
    {
        $container = new DependencyInjectionContainer();
        $components = $this->getComponents();

        foreach ($components as $component) {
            // Sometimes on failures due to invalid configuration, components might return void
            if (is_object($component)) {
                $container->register($component->getName(), $component->getInstance($container, $this->config, $this->input));
            }
        }

        return $container->getAll();
    }

    /**
     * Bootstrap the application. Prepare service location and module manager
     * But do not launch the router and controllers
     * This can be useful when you don't want to launch the application, 
     * but at the same time you want to get some service from a module
     * 
     * @return \Krystal\InstanceManager\ServiceLocator
     */
    public function bootstrap()
    {
        $this->tweak();

        $serviceLocator = new ServiceLocator();
        $serviceLocator->registerArray($this->getServices());

        // Constant that tells that the framework is launched
        define('KRYSTAL', true);

        return $serviceLocator;
    }

    /**
     * Bootstraps and runs the application!
     * 
     * @return void
     */
    public function run()
    {
        // Firstly make sure, default is set
        if (!isset($this->config['components']['router']['default'])) {
            throw new RuntimeException('You should provide default controller for router');
        }

        $sl = $this->bootstrap();

        // Grab required services to run the application
        $request    = $sl->get('request');
        $dispatcher = $sl->get('dispatcher');
        $response   = $sl->get('response');
        $middleware = $sl->get('middleware');

        // Do we need to perform SSL redirect?
        if (isset($this->config['components']['router']['ssl']) && $this->config['components']['router']['ssl'] == true) {
            $request->sslRedirect();
        }

        // We will start from route matching firstly
        $router = new Router();

        // Routes
        if (isset($this->config['components']['router']['redirects'])) {
            $router->processRedirect($request->getURI(), $this->config['components']['router']['redirects']);
        }

        $map = $dispatcher->getMap();

        // Returns RouteMatch on success, false on failure
        $route = $router->match($request->getURI(), $map);

        // 404 — middleware does not run
        if ($route === false) {
            $content = $this->renderNotFound($sl, $dispatcher);
            $response->setStatusCode(404);
            $response->send((string) $content);
            return;
        }

        // 1. Create controller without initializing it.
        //    Middleware runs before initialize() — bootstrap(), auth hooks,
        //    and translation loading are all deferred until after middleware.
        $controller = $dispatcher->createController($route);

        // 2. Merge middleware from controller and route.
        //    Order: controller middleware first, then route middleware.
        //    Duplicate detection is handled inside MiddlewareRunner::setQueue().
        $queue = array_merge(
            $controller->getMiddlewares(),
            $route->getMiddleware()
        );

        $middleware->setQueue($queue);

        $notFound = false;

        try {
            // 3. Run middleware pipeline.
            //    Returning false halts the pipeline; the response has been
            //    populated by the middleware that halted.
            if (!$middleware->run($sl, $route)) {
                $response->send('');
                return;
            }

            // 4. Initialize the controller (bootstrap, auth, translations).
            $dispatcher->initializeController($controller, $route);

            // 5. If the controller halted during initialize (haltOnDemand,
            //    auth failure, etc.), skip the action and send nothing.
            if ($controller->isHalted()) {
                $response->send('');
                return;
            }

            // 6. Invoke the action.
            $content = $dispatcher->invokeAction($controller, $route);

            // Returning false from an action triggers 404
            if ($content === false) {
                $notFound = true;
            }

        } catch (Throwable $e) {
            // Exception handler produces its own response
            $content = $this->renderException($sl, $e);
            $response->send((string) $content);
            return;

        } finally {
            // Guarantee terminate() runs on every path, including exceptions.
            // Note: terminate() only runs for middleware whose process() was
            // invoked. If the pipeline halted before any middleware ran, this
            // is a no-op.
            $middleware->terminate($sl, $route);
        }

        // Handle 404 — a controller action returned false
        if ($notFound === true) {
            $content = $this->renderNotFound($sl, $dispatcher);
            $response->setStatusCode(404);
        }

        $response->send((string) $content);
    }

    /**
     * Handles a not-found state by invoking the default route.
     *
     * The default route is configured under the router component as
     * `default` and may be either a controller notation string or a
     * callable that receives the service locator.
     *
     * @param \Krystal\InstanceManager\ServiceLocator $sl
     * @param \Krystal\Application\FrontController\Dispatcher $dispatcher
     * @throws \LogicException If the default route configuration is invalid
     * @return mixed
     */
    private function renderNotFound(ServiceLocator $sl, $dispatcher)
    {
        $default = $this->config['components']['router']['default'];

        if (is_string($default)) {
            $notation = new RouteNotation();
            $args = $notation->toArgs($default);

            // Extract controller and action from $args
            $controller = $args[0];
            $action = $args[1];

            return $dispatcher->call($controller, $action);

        } else if (is_callable($default)) {
            return call_user_func($default, $sl);
        }

        throw new LogicException(sprintf(
            'Default route must be either callable or a string that represents default controller, not %s',
            gettype($default)
        ));
    }

    /**
     * Handles a thrown exception by delegating to the exception handler.
     *
     * In production mode, the exception handler is registered during tweak(),
     * and this method is unlikely to be reached. In development mode, the
     * registered handler renders a detailed error page.
     *
     * @param \Krystal\InstanceManager\ServiceLocator $sl
     * @param \Throwable $e
     * @return string
     */
    private function renderException(ServiceLocator $sl, Throwable $e): string
    {
        // The exception handler registered during tweak() will typically
        // have already rendered and terminated. If we reach here, the
        // handler is either not registered (production mode) or has been
        // overridden. Fall back to a minimal response.
        return '';
    }

    /**
     * Initialization of standard library
     * 
     * @return void
     */
    private function tweak()
    {
        // Ignore recoverable GD errors
        ini_set('gd.jpeg_ignore_warning', 1);

        // Handle error reporting
        if (isset($this->config['production']) && false === $this->config['production']) {
            // Custom exception handler should be registered on NON-AJAX requests only
            $server = $this->input->getServer();

            // Custom exception handler
            $exceptionHandler = new ExceptionHandler($server);
            $exceptionHandler->register();

            error_reporting(self::ERR_LEVEL_MAX);
            ini_set('display_errors', 1);

        } else {
            error_reporting(self::ERR_LEVEL_NONE);
        }

        // In most cases, we require UTF-8 as a default charset
        if (!isset($this->config['charset'])) {
            ini_set('default_charset', self::DEFAULT_CHARSET);
            mb_internal_encoding(self::DEFAULT_CHARSET);

        } else {
            ini_set('default_charset', $this->config['charset']);
            mb_internal_encoding($this->config['charset']);
        }

        mb_substitute_character('none');

        // Locale
        if (isset($this->config['locale'])) {
            setlocale(LC_ALL, $this->config['locale']);
        }

        // Timezone
        if (isset($this->config['timezone'])) {
            date_default_timezone_set($this->config['timezone']);
        }

        // And lastly, magic quotes filter
        $mg = new MagicQuotesFilter();

        if ($mg->enabled()) {
            $mg->deactivate();

            $this->input->setQuery($mg->filter($this->input->getQuery()));
            $this->input->setPost($mg->filter($this->input->getPost()));
            $this->input->setCookie($mg->filter($this->input->getCookie()));

            // Third party libraries might use $_REQUEST as well, so we'd better filter this one too
            $this->input->setRequest($mg->filter($this->input->getRequest()));
        }
    }
}
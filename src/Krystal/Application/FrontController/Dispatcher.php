<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\FrontController;

use Krystal\Application\FrontController\ControllerFactory;
use Krystal\Application\FrontController\DispatcherInterface;
use Krystal\Application\Route\MapManagerInterface;
use Krystal\Application\Route\RouteMatchInterface;
use Krystal\Application\Controller\AbstractController;
use LogicException;
use UnexpectedValueException;

final class Dispatcher implements DispatcherInterface
{
    /**
     * Map manager that extracts related information from routes
     * 
     * @var \Krystal\Application\Route\MapManagerInterface
     */
    private $mapManager;

    /**
     * Responsible for building controllers and passing services to them
     * 
     * @var \Krystal\Application\FrontController\ControllerFactory
     */
    private $controllerFactory;

    /**
     * State initialization
     * 
     * @param \Krystal\Application\Route\MapManagerInterface $mapManager
     * @return void
     */
    public function __construct(MapManagerInterface $mapManager)
    {
        $this->mapManager = $mapManager;
    }

    /**
     * Sets controller factory
     * This can't be done in constructor
     * 
     * @param \Krystal\Application\FrontController\ControllerFactory $controllerFactory
     * @return void
     */
    public function setControllerFactory(ControllerFactory $controllerFactory)
    {
        $this->controllerFactory = $controllerFactory;
    }

    /**
     * Returns URI map
     * 
     * @return array
     */
    public function getURIMap()
    {
        return $this->mapManager->getURIMap();
    }

    /**
     * Returns the full route map (template => options)
     *
     * @return array
     */
    public function getMap()
    {
        return $this->mapManager->getMap();
    }

    /**
     * Creates a controller instance for the given route without initializing it.
     *
     * The controller is instantiated with its dependencies, but its initialize()
     * method is NOT called. This allows middleware to run before any controller
     * lifecycle hooks (bootstrap, auth, translations) are executed.
     *
     * Caller is responsible for calling initializeController() after middleware
     * has passed, before invoking the action.
     *
     * @param \Krystal\Application\Route\RouteMatchInterface $route
     * @return \Krystal\Application\Controller\AbstractController
     */
    public function createController(RouteMatchInterface $route): AbstractController
    {
        $template = $route->getMatchedURITemplate();

        $class   = $this->mapManager->getControllerByURITemplate($template);
        $options = $this->mapManager->getDataByUriTemplate($template);

        return $this->controllerFactory->create($class, $options);
    }

    /**
     * Initializes the given controller for the given route.
     *
     * Runs the controller's initialize() lifecycle, which in turn invokes
     * bootstrap(), auth checks, and translation loading.
     *
     * @param \Krystal\Application\Controller\AbstractController $controller
     * @param \Krystal\Application\Route\RouteMatchInterface $route
     * @return void
     */
    public function initializeController(AbstractController $controller, RouteMatchInterface $route)
    {
        $action = $this->mapManager->getActionByURITemplate($route->getMatchedURITemplate());

        $controller->initialize($action);
    }

    /**
     * Invokes the given action on the controller.
     *
     * Preserves the guards from call(): verifies the action method exists,
     * invokes it with the route's variables, and validates the return type.
     *
     * @param \Krystal\Application\Controller\AbstractController $controller
     * @param \Krystal\Application\Route\RouteMatchInterface $route
     * @throws \LogicException if the action method does not exist
     * @throws \UnexpectedValueException if the action returns an array or object
     * @return mixed Controller action return value
     */
    public function invokeAction(AbstractController $controller, RouteMatchInterface $route)
    {
        $action = $this->mapManager->getActionByURITemplate($route->getMatchedURITemplate());

        if (!method_exists($controller, $action)) {
            throw new LogicException(sprintf(
                'A %s controller must implement %s() method, because it has been defined in the map',
                get_class($controller),
                $action
            ));
        }

        $response = call_user_func_array(
            array($controller, $action),
            $route->getVariables()
        );

        if (is_array($response) || is_object($response)) {
            throw new UnexpectedValueException(sprintf(
                'A controller action must return a scalar type or null, %s returned.',
                is_object($response) ? get_class($response) : gettype($response)
            ));
        }

        return $response;
    }

    /**
     * Calls a controller providing a service locator as its dependency
     * 
     * This uses PSR-0 compliant class name, $actions as its method name
     * And an array of $params to supply into method's action
     * 
     * Note: this method is used for internal forwarding and by callers that
     * want to bypass the middleware pipeline. It builds, initializes, and
     * invokes the action in a single call. For the request lifecycle that
     * goes through middleware, use createController() / initializeController()
     * / invokeAction() explicitly.
     * 
     * @param string $class PSR-0 compliant class name
     * @param string $action A class method to be invoked
     * @param array $params Parameters to be passed into a method
     * @param array $options Route options
     * @throws \LogicException if controller hasn't expected action to execute
     * @throws \UnexpectedValueException if controller response is an array or object
     * @return mixed
     */
    public function call($class, $action, array $params = array(), array $options = array())
    {
        $controller = $this->controllerFactory->build($class, $action, $options);

        if (method_exists($controller, $action)) {
            $response = call_user_func_array(array($controller, $action), $params);

            if (is_array($response) || is_object($response)) {
                throw new UnexpectedValueException(sprintf(
                    'A controller action must return a scalar type or null, %s returned.',
                    is_object($response) ? get_class($response) : gettype($response)
                ));
            }

            return $response;
        } else {
            throw new LogicException(sprintf(
                'A %s controller must implement %s() method, because it has been defined in the map',
                $class,
                $action
            ));
        }
    }

    /**
     * Forwards to another controller from notation
     * 
     * Forwarded actions do not trigger middleware. Middleware runs once per
     * incoming HTTP request, before the initial controller's action. Internal
     * forwards invoke the target action directly.
     * 
     * @param string $notation (Controller@action syntax)
     * @param array $args Arguments to be passed to that controller's action
     * @return mixed
     */
    public function forward($notation, array $args = array())
    {
        $data = $this->mapManager->toCompliant($notation);

        $controller = array_keys($data);
        $controller = $controller[0];

        $action = array_values($data);
        $action = $action[0];

        return $this->call($controller, $action, $args);
    }
}

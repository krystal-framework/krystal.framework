<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\FrontController;

use Krystal\InstanceManager\ServiceLocatorInterface;
use Krystal\Application\Controller\AbstractController;
use RuntimeException;

final class ControllerFactory implements ControllerFactoryInterface
{
    /**
     * Service locator to be injected into every controller
     * 
     * @var \Krystal\InstanceManager\ServiceLocatorInterface
     */
    private $serviceLocator;

    /**
     * State initialization
     * 
     * @param \Krystal\InstanceManager\ServiceLocatorInterface $serviceLocator
     * @return void
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Creates a controller instance without initializing it.
     *
     * The controller is constructed with its dependencies but its
     * initialize() method is not called. This is the entry point used
     * by the middleware-aware dispatch flow.
     *
     * @param string $controller PSR-0 compliant controller class name
     * @param array $options Route options passed to the controller
     * @throws \RuntimeException if the class does not exist
     * @return \Krystal\Application\Controller\AbstractController
     */
    public function create($controller, array $options = array()): AbstractController
    {
        $class = Ns::normalize($controller);

        if (!class_exists($class)) {
            throw new RuntimeException(sprintf(
                'Controller class "%s" does not exist or does not match PSR-0 naming convention.',
                $class
            ));
        }

        $module = Ns::extractVendorNs($controller);

        return new $class($this->serviceLocator, $module, $options);
    }

    /**
     * Builds a controller and initializes it in a single call.
     *
     * This is the legacy entry point. It constructs the controller and
     * immediately invokes initialize(), which runs bootstrap(), auth
     * checks, and translation loading.
     *
     * Note: controllers that halt during initialize() (via haltOnDemand()
     * or auth hooks) will have isHalted() return true after this call.
     * Callers are responsible for checking isHalted() and short-circuiting.
     *
     * @param string $controller PSR-0 compliant controller class name
     * @param string $action Action name (used for initialize)
     * @param array $options Route options passed to the controller
     * @return \Krystal\Application\Controller\AbstractController
     */
    public function build($controller, $action, array $options = array()): AbstractController
    {
        $instance = $this->create($controller, $options);
        $instance->initialize($action);

        return $instance;
    }
}

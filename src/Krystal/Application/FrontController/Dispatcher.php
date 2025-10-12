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
use LogicException;
use RuntimeException;

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
     * Calls a controller providing a service locator as its dependency
     * 
     * This uses PSR-0 compliant class name, $actions as its method name
     * And an array of $params to supply into method's action
     * 
     * @param string $class PSR-0 compliant class name
     * @param string $action A class method to be invoked
     * @param array $params Parameters to be passed into a method
     * @param array $options Route options
     * @throws \DomainException if controller's execution is halted
     * @throws \LogicException if controller hasn't expected action to execute
     * @return string
     */
    public function call($class, $action, array $params = array(), array $options = array())
    {
        $controller = $this->controllerFactory->build($class, $action, $options);

        if (method_exists($controller, $action)) {
            return call_user_func_array(array($controller, $action), $params);
        } else {
            throw new LogicException(sprintf(
                'A %s controller must implement %s() method, because it has been defined in the map', $class, $action
            ));
        }
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
     * Forwards to another controller from notation
     * 
     * @param string $notation (Controller@action syntax)
     * @param array $args Arguments to be passed to that controller's action
     * @return string
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

    /**
     * Dispatches a controller according to the request
     * 
     * @param string $matchedURITemplate
     * @param array $params URI arguments URI place-holders
     * @return string The returned value of the controller action method
     */
    public function render($matchedURITemplate, array $params = array())
    {
        // For current URI template
        $options = $this->mapManager->getDataByUriTemplate($matchedURITemplate);

        $class = $this->mapManager->getControllerByURITemplate($matchedURITemplate);
        $action = $this->mapManager->getActionByURITemplate($matchedURITemplate);

        return $this->call($class, $action, $params, $options);
    }
}

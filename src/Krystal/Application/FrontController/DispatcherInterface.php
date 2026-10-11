<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\FrontController;

use Krystal\Application\Route\RouteMatchInterface;
use Krystal\Application\Controller\AbstractController;

interface DispatcherInterface
{
    /**
     * Returns URI map
     * 
     * @return array
     */
    public function getURIMap();

    /**
     * Returns the full route map (template => options)
     *
     * @return array
     */
    public function getMap();

    /**
     * Creates a controller instance for the given route without initializing it.
     *
     * @param \Krystal\Application\Route\RouteMatchInterface $route
     * @return \Krystal\Application\Controller\AbstractController
     */
    public function createController(RouteMatchInterface $route): AbstractController;

    /**
     * Initializes the given controller for the given route.
     *
     * @param \Krystal\Application\Controller\AbstractController $controller
     * @param \Krystal\Application\Route\RouteMatchInterface $route
     * @return void
     */
    public function initializeController(AbstractController $controller, RouteMatchInterface $route);

    /**
     * Invokes the given action on the controller.
     *
     * @param \Krystal\Application\Controller\AbstractController $controller
     * @param \Krystal\Application\Route\RouteMatchInterface $route
     * @return mixed
     */
    public function invokeAction(AbstractController $controller, RouteMatchInterface $route);

    /**
     * Calls a controller directly (bypasses middleware).
     *
     * @param string $class
     * @param string $action
     * @param array $params
     * @param array $options
     * @return mixed
     */
    public function call($class, $action, array $params = array(), array $options = array());

    /**
     * Forwards to another controller from notation.
     *
     * @param string $notation
     * @param array $args
     * @return mixed
     */
    public function forward($notation, array $args = array());
}
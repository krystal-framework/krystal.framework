<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\FrontController;

use Krystal\Application\Controller\AbstractController;

interface ControllerFactoryInterface
{
    /**
     * Creates a controller instance without initializing it.
     *
     * @param string $controller
     * @param array $options
     * @return \Krystal\Application\Controller\AbstractController
     */
    public function create($controller, array $options = array()): AbstractController;

    /**
     * Creates and initializes a controller in a single call.
     *
     * @param string $controller
     * @param string $action
     * @param array $options
     * @return \Krystal\Application\Controller\AbstractController
     */
    public function build($controller, $action, array $options = array()): AbstractController;
}

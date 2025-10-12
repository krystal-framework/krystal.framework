<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\FrontController;

interface ControllerFactoryInterface
{
    /**
     * Builds a controller instance
     * 
     * @param string $controller PSR-0 Compliant path
     * @param string $action Method to be invoked on controller
     * @param array $options Route options passed to corresponding controller
     * @return \Krystal\Application\Controller\AbstractController
     */
    public function build($controller, $action, array $options);
}

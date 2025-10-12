<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application;

interface KernelInterface
{
    /**
     * Bootstrap the application. Prepare service location and module manager
     * But do not launch the router and controllers
     * This can be useful when you don't want to launch the application, 
     * but at the same time you want to get some service from a module
     * 
     * @return \Krystal\InstanceManager\ServiceLocator
     */
    public function bootstrap();

    /**
     * Bootstraps and runs the application!
     * 
     * @return void
     */
    public function run();
}

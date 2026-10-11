<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Middleware;

use Krystal\InstanceManager\ServiceLocatorInterface;
use Krystal\Application\Route\RouteMatchInterface;

interface MiddlewareInterface
{
    /**
     * Runs before the controller action.
     * 
     * Return true to continue the pipeline. Return false to halt
     * immediately — the controller action is not invoked and the
     * response is sent as-is. On halt, the middleware is responsible
     * for setting the response status code and body.
     * 
     * @param \Krystal\InstanceManager\ServiceLocatorInterface $sl
     * @param \Krystal\Application\Route\RouteMatchInterface $route
     * @return bool True to continue, false to halt
     */
    public function process(ServiceLocatorInterface $sl, RouteMatchInterface $route): bool;

    /**
     * Runs after the response has been sent to the client.
     *
     * Intended for cleanup, logging, and side effects that should not
     * delay the response. Cannot modify the response — it has already
     * been transmitted. Runs in reverse order of process().
     *
     * @param \Krystal\InstanceManager\ServiceLocatorInterface $sl
     * @param \Krystal\Application\Route\RouteMatchInterface $route
     * @return void
     */
    public function terminate(ServiceLocatorInterface $sl, RouteMatchInterface $route);
}
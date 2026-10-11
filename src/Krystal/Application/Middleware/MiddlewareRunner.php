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
use LogicException;
use Throwable;

final class MiddlewareRunner
{
    /**
     * Middleware queue to run, in order
     *
     * @var array<int, string> Array of fully-qualified middleware class names
     */
    private $queue = [];

    /**
     * Middleware instances whose process() has been invoked
     *
     * Used to determine which middleware receive terminate() calls.
     * Retains invocation order — terminate() will walk this in reverse.
     *
     * @var array<int, MiddlewareInterface>
     */
    private $executed = [];

    /**
     * Per-request instances cache, keyed by class name
     *
     * Ensures a middleware class is only instantiated once per request,
     * even if the same class name appears in both controller and route
     * declarations after deduplication.
     *
     * @var array<string, MiddlewareInterface>
     */
    private $instances = [];

    /**
     * Sets the queue of middleware to run.
     *
     * Performs duplicate detection across the full merged list.
     * Throws if the same class appears more than once.
     *
     * @param array<int, string> $queue Array of middleware class names
     * @throws \LogicException If a duplicate class name is detected
     * @return void
     */
    public function setQueue(array $queue)
    {
        $seen = [];

        foreach ($queue as $class) {
            if (isset($seen[$class])) {
                throw new LogicException(sprintf(
                    'Duplicate middleware declaration: "%s" appears more than once in the middleware queue.',
                    $class
                ));
            }

            $seen[$class] = true;
        }

        $this->queue = array_values($queue);
    }

    /**
     * Runs all process() methods in queue order.
     *
     * Returns true to continue to the controller action, or false if a
     * middleware halted the pipeline. On halt, the response object has
     * already been populated by the middleware that halted.
     *
     * @param \Krystal\InstanceManager\ServiceLocatorInterface $sl
     * @param \Krystal\Application\Route\RouteMatchInterface $route
     * @return bool True to continue, false to halt
     */
    public function run(ServiceLocatorInterface $sl, RouteMatchInterface $route): bool
    {
        $this->executed = [];

        foreach ($this->queue as $class) {
            $middleware = $this->resolve($class);

            // Record BEFORE invoking process(), so that even a
            // halting middleware is registered for terminate().
            $this->executed[] = $middleware;

            if ($middleware->process($sl, $route) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Runs terminate() on all executed middleware in reverse order.
     *
     * Wrapped in try/catch per middleware to ensure a single failure
     * does not prevent the rest from running. Failures are swallowed —
     * the response has already been sent at this point.
     *
     * @param \Krystal\InstanceManager\ServiceLocatorInterface $sl
     * @param \Krystal\Application\Route\RouteMatchInterface $route
     * @return void
     */
    public function terminate(ServiceLocatorInterface $sl, RouteMatchInterface $route)
    {
        foreach (array_reverse($this->executed) as $middleware) {
            try {
                $middleware->terminate($sl, $route);
            } catch (Throwable $e) {
                // Response has already been sent. Nothing useful can be done
                // here that doesn't involve a logging service we don't have.
                // Swallow and continue — other middleware still need to run.
            }
        }
    }

    /**
     * Resolves a middleware instance by class name.
     *
     * Instantiates on first use and caches the instance for the duration
     * of the request. Middleware is required to have a no-argument
     * constructor; all services come from the service locator.
     *
     * @param string $class Fully-qualified middleware class name
     * @throws \LogicException If the class does not exist or does not implement the contract
     * @return \Krystal\Application\Middleware\MiddlewareInterface
     */
    private function resolve(string $class): MiddlewareInterface
    {
        if (isset($this->instances[$class])) {
            return $this->instances[$class];
        }

        if (!class_exists($class)) {
            throw new LogicException(sprintf(
                'Middleware class "%s" does not exist.',
                $class
            ));
        }

        $instance = new $class();

        if (!$instance instanceof MiddlewareInterface) {
            throw new LogicException(sprintf(
                'Middleware "%s" must implement %s.',
                $class,
                MiddlewareInterface::class
            ));
        }

        $this->instances[$class] = $instance;

        return $instance;
    }
}
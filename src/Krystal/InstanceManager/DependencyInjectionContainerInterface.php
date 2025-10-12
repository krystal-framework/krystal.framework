<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\InstanceManager;

interface DependencyInjectionContainerInterface
{
    /**
     * Adds a parameter to a callable closure when registering a service
     * 
     * @param mixed $param
     * @return \Krystal\InstanceManager\DependencyInjectionContainer
     */
    public function addParam($param);

    /**
     * Add parameters to a callable closure when registering a service
     * 
     * @param array $params
     * @return \Krystal\InstanceManager\DependencyInjectionContainer
     */
    public function addParams(array $params);

    /**
     * Registers a service
     * 
     * @param string $name
     * @param mixed $handler Either a closure or an instance
     * @return \Krystal\InstanceManager\DependencyInjectionContainer
     */
    public function register($name, $handler);

    /**
     * Registers a collection of services
     * 
     * @param array $collection
     * @return \Krystal\InstanceManager\DependencyInjectionContainer
     */
    public function registerCollection(array $collection);

    /**
     * Returns all registered services
     * 
     * @return array
     */
    public function getAll();

    /**
     * Returns a service by its name
     * 
     * @param string $name
     * @throws \RuntimeException If attempted to get non-existing service
     * @return object
     */
    public function get($name);

    /**
     * Checks whether service exists
     * 
     * @param string $name
     * @return boolean
     */
    public function exists($name);
}

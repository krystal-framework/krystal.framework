<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\InstanceManager;

interface ServiceLocatorInterface
{
	/**
	 * Returns all registered services
	 * 
	 * @return array
	 */
	public function getAll();

	/**
	 * Returns service's instance by its name
	 * 
	 * @param string $service Service name
	 * @throws \RuntimeException if attempted to return non-existing service
	 * @return object
	 */
	public function get($service);

	/**
	 * Registers a collection of service instances
	 * 
	 * @param array $instances
	 * @return \Krystal\InstanceManager\ServiceLocator
	 */
	public function registerArray(array $instances);

	/**
	 * Registers a new service
	 * 
	 * @param string $name
	 * @param object $instance
	 * @throws \InvalidArgumentException if service name isn't string
	 * @return \Krystal\InstanceManager\ServiceLocator
	 */
	public function register($name, $instance);

	/**
	 * Check whether service has been registered
	 * 
	 * @param string $service
	 * @throws \InvalidArgumentException if $service wasn't string
	 * @return boolean
	 */
	public function has($service);

	/**
	 * Removes a registered service
	 * 
	 * @param string $service
	 * @throws \InvalidArgumentException if $service_name wasn't string
	 * @return boolean Depending on success
	 */
	public function remove($service);
}

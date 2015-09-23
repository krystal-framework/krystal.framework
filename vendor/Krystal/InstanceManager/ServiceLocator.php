<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\InstanceManager;

use RuntimeException;
use InvalidArgumentException;

final class ServiceLocator implements ServiceLocatorInterface
{
	/**
	 * Availabe service instances
	 * 
	 * @var array
	 */
	private $container = array();

	/**
	 * State initialization
	 * 
	 * @param array $services Services can be optinally registered on instantiation
	 * @return void
	 */
	public function __construct(array $services = array())
	{
		if (!empty($services)) {
			$this->registerArray($services);
		}
	}

	/**
	 * Returns all registered services
	 * 
	 * @return array
	 */
	public function getAll()
	{
		return $this->container;
	}

	/**
	 * Returns service's instance by its name
	 * 
	 * @param string $service Service name
	 * @throws \RuntimeException if attempted to return non-existing service
	 * @return object
	 */
	public function get($service)
	{
		if ($this->has($service)) {
			return $this->container[$service];
		} else {
			throw new RuntimeException(sprintf(
				'Attempted to grab non-existing service "%s"', $service
			));
		}
	}

	/**
	 * Registers a collection of service instances
	 * 
	 * @param array $instances
	 * @return \Krystal\InstanceManager\ServiceLocator
	 */
	public function registerArray(array $instances)
	{
		foreach ($instances as $name => $instance) {
			$this->register($name, $instance);
		}

		return $this;
	}

	/**
	 * Registers a new service
	 * 
	 * @param string $name
	 * @param object $instance
	 * @throws \InvalidArgumentException if service name isn't string
	 * @return \Krystal\InstanceManager\ServiceLocator
	 */
	public function register($name, $instance)
	{
		if (!is_string($name)) {
			throw new InvalidArgumentException(sprintf(
				'Argument #1 $name must be a string, received "%s"', gettype($name)
			));
		}

		$this->container[$name] = $instance;
		return $this;
	}

	/**
	 * Check whether service has been registered
	 * 
	 * @param string $service
	 * @throws \InvalidArgumentException if service's name wasn't a string
	 * @return boolean
	 */
	public function has($service)
	{
		if (!is_string($service)) {
			throw new InvalidArgumentException(sprintf(
				'Service name must be a string, received "%s"', gettype($service)
			));
		}

		return isset($this->container[$service]);
	}

	/**
	 * Removes a registered service
	 * 
	 * @param string $service
	 * @throws \InvalidArgumentException if $service_name wasn't string
	 * @return boolean Depending on success
	 */
	public function remove($service)
	{
		if ($this->exists($serviceName)) {
			unset($this->container[$serviceName]);
			return true;
		} else {
			trigger_error(sprintf(
				'Attempted to un-register non-existing service "%s"', $service
			));

			return false;
		}
	}
}

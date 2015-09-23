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

final class DependencyInjectionContainer implements DependencyInjectionContainerInterface
{
	/**
	 * Dependency container
	 * 
	 * @var array
	 */
	private $container = array();

	/**
	 * Extra parameters passed to each closure
	 * 
	 * @var array
	 */
	private $params = array();

	/**
	 * State initialization
	 * 
	 * @param array $params
	 * @return void
	 */
	public function __construct(array $params = array())
	{
		if (!empty($params)) {
			$this->addParams($params);
		}
	}

	/**
	 * Adds a parameter to a callable closure when registering a service
	 * 
	 * @param mixed $param
	 * @return void
	 */
	public function addParam($param)
	{
		array_push($this->params, $param);
	}

	/**
	 * Add parameters to a callable closure when registering a service
	 * 
	 * @param array $params
	 * @return void
	 */
	public function addParams(array $params)
	{
		foreach ($params as $param) {
			$this->addParam($param);
		}
	}

	/**
	 * Registers a service
	 * 
	 * @param string $name
	 * @param mixed $handler Either a closure or an instance
	 * @return void
	 */
	public function register($name, $handler)
	{
		if (is_callable($handler)) {
			$params = array_merge(array($this), $this->params);
			$this->container[$name] = call_user_func_array($handler, $params);

		} else {
			$this->container[$name] = $handler;
		}
	}

	/**
	 * Registers a collection of services
	 * 
	 * @param array $collection
	 * @return void
	 */
	public function registerCollection(array $collection)
	{
		foreach ($collection as $name => $handler) {
			$this->register($name, $handler);
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
	 * Returns a service by its name
	 * 
	 * @param string $name
	 * @throws \RuntimeException If attempted to get non-existing service
	 * @return object
	 */
	public function get($name)
	{
		if ($this->exists($name)) {
			return $this->container[$name];
		} else {
			throw new RuntimeException(sprintf('Attempted to retrieve non-existing dependency "%s"', $name));
		}
	}

	/**
	 * Checks whether service exists
	 * 
	 * @param string $name
	 * @return boolean
	 */
	public function exists($name)
	{
		return isset($this->container[$name]);
	}
}

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

use ReflectionClass;

final class InstanceBuilder implements InstanceBuilderInterface
{
	/**
	 * Already instantiated objects
	 * 
	 * @var array
	 */
	private $cache = array();

	/**
	 * Builds an instance of a class passing arguments to its constructor
	 * 
	 * @param string $class PSR-0 compliant class name
	 * @param array $args Arguments to be passed to class's contructor
	 * @throws \RuntimeException If attempting to build non-existing class
	 * @return object
	 */
	public function build($class, array $args)
	{
		// Normalize class name
		$class = $this->normalizeClassName($class);

		if (isset($this->cache[$class])) {
			return $this->cache[$class];
		} else {
			if (class_exists($class, true)) {

				$instance = $this->getInstance($class, $args);
				$this->cache[$class] = $instance;

				return $instance;

			} else {
				throw new RuntimeException(sprintf(
					'Can not build non-existing class "%s". The class does not exist or it does not follow PSR-0', $class
				));
			}
		}
	}

	/**
	 * Normalizes class name
	 * 
	 * @param string $class PSR-compliant class name
	 * @return string
	 */
	private function normalizeClassName($class)
	{
		$class = rtrim($class, '\\');
		$class = str_replace('/', '\\', $class);

		return $class;
	}

	/**
	 * Builds and returns an instance of a class
	 * 
	 * @param string $class PSR-0 compliant class name
	 * @param array $args Arguments to be passed to class's contructor
	 * @return object
	 */
	private function getInstance($class, array $args)
	{
		// Hack to avoid Reflection for most cases
		switch (count($args)) {
			case 0:
				return new $class;
			case 1:
				return new $class($args[0]);
			case 2:
				return new $class($args[0], $args[1]);
			case 3:
				return new $class($args[0], $args[1], $args[2]);
			case 4:
				return new $class($args[0], $args[1], $args[2], $args[3]);
			case 5:
				return new $class($args[0], $args[1], $args[2], $args[3], $args[4]);
			case 6:
				return new $class($args[0], $args[1], $args[2], $args[3], $args[4], $args[5]);
			default:
				$reflection = new ReflectionClass();
				return $reflection->newInstanceArgs($args);
		}
	}
}

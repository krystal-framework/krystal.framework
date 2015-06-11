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

final class InstanceProvider
{
	/**
	 * State initialization
	 * 
	 * @param array $data
	 * @return void
	 */
	public function __construct(array $data)
	{
		$this->data = $data;
	}

	/**
	 * Returns all instances of class which has been loaded before
	 * 
	 * @return array Array of instances
	 */
	public function getAll()
	{
		$instances = array();

		foreach ($this->data as $className => $args) {
			if (class_exists($className)) {

				$reflector = new ReflectionClass($className);
				$instance = $reflector->newInstanceArgs($args);

				array_push($instances, $instance);
			}
		}

		return $instances;
	}
}

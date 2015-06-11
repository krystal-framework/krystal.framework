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

class ObjectContainer
{
	/**
	 * Object dependencies
	 * 
	 * @var array
	 */
	protected $deps = array();
	
	/**
	 * @var array
	 */
	protected $cache = array();
	
	/**
	 * State initialization
	 * 
	 * @return void
	 */
	public function __construct()
	{
	}
	
	/**
	 * Flushes the container
	 * 
	 * @return void
	 */
	public function flush()
	{
		$this->deps = array();
		$this->cache = array();
	}
	
	/**
	 * Adds a dependency
	 * 
	 * @param object $dependency
	 * @return ObjectContainer
	 */
	public function addDependency($dependency)
	{
		array_push($this->deps, $dependency);
		return $this;
	}
	
	/**
	 * Add dependencies
	 * 
	 * @param array $dependencies
	 * @return ObjectContainer
	 */
	public function addDependencies(array $dependencies)
	{
		foreach ($dependencies as $dependency) {
			$this->addDependency($dependency);
		}
		
		return $this;
	}
	
	/**
	 * Builds an object
	 * 
	 * @param string $namespace
	 * @return object
	 */
	public function build($namespace)
	{
		$namespace = rtrim($namespace, '\\');
		$namespace = str_replace('/', '\\', $namespace);
		
		// Attempt to autoload a namespace
		if (class_exists($namespace)) {
			
			if (!array_key_exists($namespace, $this->cache)) {
				
				$reflection = new ReflectionClass($namespace);
				$instance = $reflection->newInstanceArgs($this->deps);
				
				$this->cache[$namespace] = $instance;
				
				return $instance;
				
			} else {
				
				echo 'from cache';
				
				return $this->cache[$namespace];
			}
			
		} else {
			trigger_error(sprintf('Attempted to read non-existing class "%s"', $namespace));
		}
	}
}

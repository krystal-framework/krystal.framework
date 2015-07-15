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

use RuntimeException;
use ReflectionClass;

class Factory
{
	/**
	 * Target namespace
	 * 
	 * @var string
	 */
	protected $namespace;

	/**
	 * Defines a namespace
	 * 
	 * @param string $namespace
	 * @return void
	 */
	final public function setNamespace($namespace)
	{
		$this->namespace = $namespace;
	}

	/**
	 * Returns a namespace
	 * 
	 * @return string
	 */
	final public function getNamespace()
	{
		return $this->namespace;
	}

	/**
	 * Builds a classname according to defined pseudo-namespace
	 * 
	 * @param string $filename (Without extension and base path)
	 *                         As we defined it in the constructor
	 * @return string
	 */
	final protected function buildClassNameByFileName($filename)
	{
		$className = sprintf('%s/%s', $this->getNamespace(), $filename);
		// Normalize it
		$className = str_replace(array('//', '/', '\\'), '\\', $className);

		return $className;
	}

	/**
	 * Builds an instance according to the class name with its pseudo-namespace
	 * 
	 * @param string $className including pseudo-namespace (PSR-0 compliant)
	 * @param array $arguments Optionally
	 * @return object
	 */
	final protected function buildInstance($className, array $arguments)
	{
		// @TODO: Make hack to avoid reflection is most cases
		$reflector = new ReflectionClass($className);

		if ($reflector->hasMethod('__construct')) {
			return $reflector->newInstanceArgs($arguments);
		} else {
			return $reflector->newInstance();
		}
	}

	/**
	 * Builds an instance
	 * Heavily relies on PSR-0 autoloader
	 * 
	 * @param string $filename (Without extension and base path)
	 * @param mixed $arguments [...]
	 * @throws RuntimeException if cannot load a class
	 * @return object
	 */
	final public function build()
	{
		$arguments = func_get_args();
		$filename = array_shift($arguments);

		$className = $this->buildClassNameByFileName($filename);

		/**
		 * This heavily relies on PSR-0 autoloader
		 * Checks whether a class has been loaded before,
		 * If not, then attempts to load it via registered autoloader
		 */
		if (class_exists($className, true)) {
			return $this->buildInstance($className, $arguments);
		} else {
			// Couldn't load a class
			throw new RuntimeException(sprintf(
				'Could not load a class %s via registered autoloader', $className
			));
		}
	}
}

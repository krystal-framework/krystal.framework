<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Autoloader;

/* The autoloader is not ready yet*/
require_once(__DIR__ . '/AbstractSplLoader.php');

use LogicException;

/**
 * PSR0 autoloader implementation
 * 
 * http://www.php-fig.org/psr/psr-0/
 */
final class PSR0 extends AbstractSplLoader
{
	/**
	 * The base directories being inspected
	 * 
	 * @var string
	 */
	private $dirs = array();

	/**
	 * {@inheritDoc}
	 */
	public function loadClass($class)
	{
		foreach ($this->dirs as $dir) {
			$file = $this->buildPath($dir, $class);

			if ($this->includeClass($file)) {
				// Break the iteration and return TRUE, indicating success
				return true;
			}
		}

		// By default
		return false;
	}

	/**
	 * Add a base directory
	 * 
	 * @param string $dir
	 * @throws \LogicException if $dir isn't path to a directory on the file-system
	 * @return \Krystal\Autoloader\PSR0
	 */
	public function addDir($dir)
	{
		if (!is_dir($dir)) {
			throw new LogicException(sprintf(
				'Provided path "%s" is not a directory', $dir
			));
		}

		array_push($this->dirs, $dir);
		return $this;
	}

	/**
	 * Add base directories
	 * 
	 * @param array $dirs
	 * @return \Krystal\Autoloader\PSR0
	 */
	public function addDirs(array $dirs)
	{
		foreach ($dirs as $dir) {
			$this->addDir($dir);
		}
		
		return $this;
	}

	/**
	 * Returns all working directories
	 * 
	 * @return array
	 */
	public function getDirs()
	{
		return $this->dirs;
	}

	/**
	 * Converts namespace to a class name
	 * 
	 * @param string $namespace Class Namespace like, Foo_Bar
	 * @return string Class Name
	 */
	private function toClassName($namespace)
	{
		return str_replace('\\', \DIRECTORY_SEPARATOR, $namespace);
	}

	/**
	 * Builds file path for inclusion
	 * 
	 * @param string $dir Base directory
	 * @param string $class Class name
	 * @return string
	 */
	private function buildPath($dir, $class)
	{
		return $dir . \DIRECTORY_SEPARATOR . $this->toClassName($class).self::EXTENSTION;
	}
}

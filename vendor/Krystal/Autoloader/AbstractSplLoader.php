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

abstract class AbstractSplLoader
{
	/**
	 * Default extension
	 * 
	 * @const string
	 */
	const EXTENSTION = '.php';

	/**
	 * Attempts to include a class from a file
	 * 
	 * @param string $file
	 * @return boolean Depending on success
	 */
	final protected function includeClass($file)
	{
		if (is_file($file)) {
			
			include($file);
			return true;
			
		} else {
			return false;
		}
	}

	/**
	 * Attempts to load a class or an interface
	 * 
	 * @param string $class Missed class name
	 * @return boolean Depending on success
	 */
	abstract public function loadClass($class);

	/**
	 * Registers autoloader
	 * 
	 * @param string $method
	 * @return boolean
	 */
	public function register()
	{
		return spl_autoload_register(array($this, 'loadClass'));
	}

	/**
	 * Un-registers loaded
	 * 
	 * @return void
	 */
	public function unregister()
	{
		return spl_autoload_unregister(array($this, 'loadClass'));
	}
}

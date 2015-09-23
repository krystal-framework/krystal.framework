<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Config;

/* Each configuration service must implement this interface */
interface ConfigServiceInterface
{
	/**
	 * Stores configuration's entry
	 * 
	 * @param string $module
	 * @param string $name
	 * @param string $value
	 * @return boolean
	 */
	public function store($module, $name, $value);

	/**
	 * Returns all configuration entries by associated module
	 * 
	 * @param string $module
	 * @return array
	 */
	public function getAllByModule($module);

	/**
	 * Returns configuration entry from the cache
	 * 
	 * @param string $module
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($module, $name, $default = false);

	/**
	 * Checks configuration's entry exists in a module
	 * 
	 * @param string $module
	 * @param string $name
	 * @return boolean
	 */
	public function has($module, $name);

	/**
	 * Removes all configuration
	 * 
	 * @return boolean
	 */
	public function removeAll();

	/**
	 * Removes a configuration's name and value by associated module
	 * 
	 * @param string $module
	 * @param string $name
	 * @return boolean
	 */
	public function remove($module, $name);

	/**
	 * Removes all configuration data by associated module
	 * 
	 * @param string $module
	 * @return boolean
	 */
	public function removeAllByModule($module);
}

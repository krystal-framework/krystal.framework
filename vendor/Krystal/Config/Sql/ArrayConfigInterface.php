<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Config\Sql;

interface ArrayConfigInterface
{
	/**
	 * Sets configuration data
	 * 
	 * @param array $data
	 * @return void
	 */
	public function setData(array $data);

	/**
	 * Removes all configuration data
	 * 
	 * @return void
	 */
	public function clear();

	/**
	 * Removes by module and its associated name
	 * 
	 * @param string $module
	 * @param string $name
	 * @return void
	 */
	public function remove($module, $name);

	/**
	 * Removes all by associated module
	 * 
	 * @param string $module
	 * @return void
	 */
	public function removeAllByModule($module);

	/**
	 * Returns all data by associated module
	 * 
	 * @param string $module
	 * @return array|boolean
	 */
	public function getAllByModule($module);

	/**
	 * Returns configuration entry
	 * 
	 * @param string $module
	 * @param string $name
	 * @param mixed $default Default value to be returned in case requested one doesn't exist
	 * @return mixed
	 */
	public function get($module, $name, $default);

	/**
	 * Adds configuration data
	 * 
	 * @param string $module
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 */
	public function add($module, $name, $value);

	/**
	 * Updates existing pair with new value
	 * 
	 * @param string $module
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 */
	public function update($module, $name, $value);

	/**
	 * Checks whether there's at least one module in the stack with provided name
	 * 
	 * @param string $module
	 * @return boolean
	 */
	public function hasModule($module);

	/**
	 * Checks whether module has a specific key
	 * 
	 * @param string $module
	 * @param string $name
	 * @param integer $position For internal usage only
	 * @return boolean
	 */
	public function has($module, $name, &$position = false);
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Config;

use Krystal\Config\ConfigServiceInterface;

final class FileConfigService implements ConfigServiceInterface
{
	/**
	 * @var 
	 */
	private $arrayCache;

	/**
	 * @var 
	 */
	private $arraySignature;

	/**
	 * @var 
	 */
	private $fileAdapter;
	
	/**
	 * State initialization
	 * 
	 * @param $arrayCache
	 * @param 
	 * @return void
	 */
	public function __construct($arrayCache, $arraySignature, $fileAdapter)
	{
		$this->arrayCache = $arrayCache;
		$this->arraySignature = $arraySignature;
		$this->fileAdapter = $fileAdapter;
	}

	/**
	 * Saves the data
	 */
	function save()
	{
		$data = $this->arrayCache->getData();

		if ($this->arraySignature->hasChanged($data)) {
			return $this->fileAdapter->write($data);
		}
	}

	/**
	 * Service initialization
	 * 
	 * @return void
	 */
	public function initialize()
	{
		$data = $this->fileAdapter->getData();
		$this->arrayConfig->setData($data);
	}

	/**
	 * Stores configuration's entry
	 * 
	 * @param string $module
	 * @param string $name
	 * @param string $value
	 * @return boolean
	 */
	public function store($module, $name, $value)
	{
		return $this->arrayConfig->add($module, $name, $value);
	}

	/**
	 * Returns all configuration entries by associated module
	 * 
	 * @param string $module
	 * @return array
	 */
	public function getAllByModule($module)
	{
		return $this->arrayConfig->getAllByModule($module);
	}

	/**
	 * Returns configuration entry from the cache
	 * 
	 * @param string $module
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($module, $name, $default = false)
	{
		return $this->arrayConfig->get($module, $name, $default);
	}

	/**
	 * Checks configuration's entry exists in a module
	 * 
	 * @param string $module
	 * @param string $name
	 * @return boolean
	 */
	public function has($module, $name)
	{
		return $this->arrayConfig->has($module, $name);
	}

	/**
	 * Removes all configuration
	 * 
	 * @return boolean
	 */
	public function removeAll()
	{
		$this->arrayConfig->clear();
		return true;
	}

	/**
	 * Removes a configuration's name and value by associated module
	 * 
	 * @param string $module
	 * @param string $name
	 * @return boolean
	 */
	public function remove($module, $name)
	{
		if ($this->exists($module, $name)) {

			$this->arrayConfig->remove($module, $name);
			return true;

		} else {
			return false;
		}
	}

	/**
	 * Removes all configuration data by associated module
	 * 
	 * @param string $module
	 * @return boolean
	 */
	public function removeAllByModule($module)
	{
		if ($this->arrayConfig->hasModule($module)) {

			$this->arrayConfig->removeAllByModule($module);
			return true;

		} else {
			return false;
		}
	}
}

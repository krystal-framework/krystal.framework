<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Cache;

interface CacheEngineInterface
{
	/**
	 * Increments numeric value
	 * 
	 * @param string $key
	 * @param integer $step
	 * @return boolean
	 */
	public function increment($key, $step = 1);

	/**
	 * Decrements numeric value
	 * 
	 * @param string $key
	 * @param integer $step
	 * @return boolean
	 */
	public function decrement($key, $step = 1);

	/**
	 * Stores the data to the cache
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @param integer $ttl Time to live in seconds
	 * @return boolean
	 */
	public function set($key, $value, $ttl);

	/**
	 * Reads key's value from a cache if present
	 * 
	 * @param string $key
	 * @param boolean $default Default value to be returned if required key doesn't exist
	 * @return mixed
	 */
	public function get($key, $default = false);

	/**
	 * Removes a key from cache
	 * 
	 * @param string $key
	 * @return boolean True if removed, false if could not
	 */
	public function remove($key);

	/**
	 * Check whether key exists in cache
	 * 
	 * @param string $key
	 * @return boolean True if exists, false otherwise
	 */
	public function has($key);

	/**
	 * Returns all cache data
	 * 
	 * @return array
	 */
	public function getAll();
}

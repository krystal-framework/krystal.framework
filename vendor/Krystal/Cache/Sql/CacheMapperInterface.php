<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Cache\Sql;

interface CacheMapperInterface
{
	/**
	 * Flushes the cache
	 * 
	 * @return boolean
	 */
	public function flush();

	/**
	 * Fetches cache data
	 * 
	 * @return array
	 */
	public function fetchAll();

	/**
	 * Increments a field
	 * 
	 * @param string $key
	 * @param integer $step
	 * @return boolean
	 */
	public function increment($key, $step);

	/**
	 * Decrements a field
	 * 
	 * @param string $key
	 * @param integer $step
	 * @return boolean
	 */
	public function decrement($key, $step);

	/**
	 * Inserts new cache's record
	 * 
	 * @param string $key
	 * @param mixed
	 * @param integer $ttl Time to live in seconds
	 * @param integer $time Current timestamp
	 * @return boolean
	 */
	public function insert($key, $value, $ttl, $time);

	/**
	 * Updates cache's entry
	 * 
	 * @param string $key
	 * @param string $value
	 * @param integer $ttl
	 * @return boolean
	 */
	public function update($key, $value, $ttl);

	/**
	 * Removes an entry from the cache
	 * 
	 * @param string $key
	 * @return boolean
	 */
	public function delete($key);
}

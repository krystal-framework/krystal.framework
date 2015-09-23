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

use BadMethodCallException;

/**
 * XCache storage engine
 * 
 * @link http://xcache.lighttpd.net/wiki/XcacheApi
 */
final class XCache implements CacheEngineInterface
{
	/**
	 * Returns all cache data
	 * 
	 * @return array
	 */
	public function getAll()
	{
		throw new BadMethodCallException('The getAll() method is not available in XCache engine');
	}

	/**
	 * Increments numeric value
	 * 
	 * @param string $key
	 * @param integer $step
	 * @return integer
	 */
	public function increment($key, $step = 1)
	{
		return xcache_inc($key, $step);
	}

	/**
	 * Decrements a numeric value
	 * 
	 * @param string $key
	 * @param integer $step
	 * @return integer
	 */
	public function decrement($key, $step = 1)
	{
		return xcache_dec($key, $step);
	}

	/**
	 * Count number of items
	 * 
 	 * @return integer
	 */
	public function count()
	{
		return xcache_count(\XC_TYPE_VAR);
	}

	/**
	 * Flushes the cache
	 * 
	 * @return boolean
	 */
	public function flush()
	{
		$i = 0;
		$count = $this->count();

		while ($i < $count) {
			if (xcache_clear_cache(XC_TYPE_VAR, $i) === false) {
				return false;
			}

			$i++;
		}

		return true;
	}

	/**
	 * Reads an item if it exists
	 * 
	 * @param string $key
	 * @param boolean $default Default value to be returned if required key doesn't exist
	 * @return mixed
	 */
	public function get($key, $default = false)
	{
		if ($this->has($key)) {
			return xcache_get($key);
		} else {
			return $default;
		}
	}

	/**
	 * Writes data to cache
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @param integer $ttl
	 * @return boolean
	 */
	public function set($key, $value, $ttl)
	{
		return xcache_set($key, $value, $ttl);
	}

	/**
	 * Deletes an item from cache
	 * 
	 * @param string $key
	 * @return boolean
	 */
	public function remove($key)
	{
		return xcache_unset($key);
	}

	/**
	 * Determine whether cache key exists
	 * 
	 * @param string $key
	 * @return boolean
	 */
	public function has($key)
	{
		return xcache_isset($key);
	}
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Cache\Memcached;

use Memcached;
use Krystal\Cache\CacheEngineInterface;

final class MemcachedEngine implements CacheEngineInterface
{
	/**
	 * Prepared \Memcached instance
	 * 
	 * @var \Memcached
	 */
	private $memcached;

	/**
	 * State initialization
	 * 
	 * @param \Memcached $memcached
	 * @return void
	 */
	public function __construct(Memcached $memcached)
	{
		$this->memcached = $memcached;
	}

	/**
	 * Flushes the cache
	 * 
	 * @return boolean
	 */
	public function flush()
	{
		return $this->memcached->flush(); 
	}

	/**
	 * Increments a numeric value
	 * 
	 * @param string $key
	 * @param integer $step
	 * @return void
	 */
	public function increment($key, $step = 1)
	{
		return $this->memcached->increment($key, $step);
	}

	/**
	 * Decrements a numeric value
	 * 
	 * @param string $key
	 * @param integer $step
	 * @return void
	 */
	public function decrement($key, $step = 1)
	{
		return $this->memcached->decrement($key, $step);
	}

	/**
	 * Stores into the cache
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @param integer $ttl
	 * @return boolean
	 */
	public function set($key, $value, $ttl)
	{
		if (!$this->memcached->set($key, $value, $ttl)) {
			// If set() returns false, that means the key already exists,
			return $this->memcached->replace($key, $value, $ttl);

		} else {
			// set() returned true, so the key has been set successfully
			return true;
		}
	}

	/**
	 * Returns from the cache
	 * 
	 * @param string $key
	 * @param boolean $default
	 * @return mixed
	 */
	public function get($key, $default = false)
	{
		$value = $this->memcached->get($key);

		if ($this->memcached->getResultCode() == 0) {
			return $value;
		} else {
			return $default;
		}
	}

	/**
	 * Checks whether cache key exists
	 * 
	 * @param string $key
	 * @return boolean
	 */
	public function has($key)
	{
		// Got help from here: http://stackoverflow.com/a/3091136/1208233
		if (!$this->memcached->add($key, true, 10)) {
			// If not possible to add, then the key exists
			return true;

		} else {
			// If possible to add, then the key doesn't exist
			$this->memcached->delete($key);
			return false;
		}
	}

	/**
	 * Removes a key from the cache
	 * 
	 * @param string $key
	 * @return boolean
	 */
	public function remove($key)
	{
		return $this->memcached->delete($key);
	}

	/**
	 * Returns all cache entries
	 * 
	 * @return array
	 */
	public function getAll()
	{
		return $this->memcached->fetchAll();
	}
}

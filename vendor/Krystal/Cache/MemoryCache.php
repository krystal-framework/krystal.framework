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

use InvalidArgumentException;

final class MemoryCache implements CacheEngineInterface
{
    /**
     * Data container
     * 
     * @var array
     */
    private $cache = array();

    /**
     * Increments a numeric value
     * 
     * @param string $key
     * @param integer $step
     * @return void
     */
    public function increment($key, $step = 1)
    {
        $value = $this->get($key);
        $this->set($key, $value + $step);
    }

    /**
     * Decrements a numeric value
     * 
     * @param string $string $key
     * @param integer $step
     * @return void
     */
    public function decrement($key, $step = 1)
    {
        $value = $this->get($key);
        $this->set($key, $value - $step);
    }

    /**
     * Writes data to cache
     * 
     * @param string $key
     * @param string $value
     * @param integer $ttl Time to live is ignored
     * @return boolean TRUE always
     */
    public function set($key, $value, $ttl)
    {
        $this->cache[$key] = $value;
        return true;
    }

    /**
     * Reads key's value from a cache if present
     * 
     * @param string $key
     * @param boolean $default Default value to be returned if required key doesn't exist
     * @return mixed
     */
    public function get($key, $default = false)
    {
        if ($this->has($key) !== false) {
            return $this->cache[$key];
        } else {
            return $default;
        }
    }

    /**
     * Removes a key from the memory
     * 
     * @param string $key
     * @return boolean True if removed, false if could not
     */
    public function remove($key)
    {
        if ($this->has($key)) {
            unset($this->cache[$key]);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check whether key exists in cache
     * 
     * @param string $key
     * @return boolean True if exists, false otherwise
     */
    public function has($key)
    {
        return array_key_exists($key, $this->cache);
    }

    /**
     * Returns all cache data
     * 
     * @return array
     */
    public function getAll()
    {
        return $this->cache;
    }
}

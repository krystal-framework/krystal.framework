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

final class NullCache implements CacheEngineInterface
{
    use CacheTrait;

    /**
     * Increments a numeric value
     * 
     * @param string $key
     * @param integer $step
     * @return void
     */
    public function increment($key, $step = 1)
    {
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
        return $default;
    }

    /**
     * Removes a key from the memory
     * 
     * @param string $key
     * @return boolean True if removed, false if could not
     */
    public function remove($key)
    {
        return false;
    }

    /**
     * Check whether key exists in cache
     * 
     * @param string $key
     * @return boolean True if exists, false otherwise
     */
    public function has($key)
    {
        return false;
    }

    /**
     * Returns all cache data
     * 
     * @return array
     */
    public function getAll()
    {
        return array();
    }
}

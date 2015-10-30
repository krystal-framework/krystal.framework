<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Cache\FileEngine;

interface ArrayCacheInterface
{
    /**
     * Decrements a value
     * 
     * @param string $key
     * @param integer $step
     * @return void
     */
    public function decrement($key, $step);

    /**
     * Increments a value
     * 
     * @param string $key
     * @param integer $step
     * @return void
     */
    public function increment($key, $step);

    /**
     * Clears the data
     * 
     * @return void
     */
    public function clear();

    /**
     * Sets the cache data
     * 
     * @param array $data
     * @return void
     */
    public function setData(array $data);

    /**
     * Returns cache data
     * 
     * @return array
     */
    public function getData();

    /**
     * Returns cache data as key => value pair
     * 
     * @return array
     */
    public function getAsPair();

    /**
     * Checks whether cache key is expired
     * 
     * @param string $key
     * @param integer $time Current timestamp
     * @return boolean
     */
    public function isExpired($key, $time);

    /**
     * Sets the cache data
     * 
     * @param string $key
     * @param mixed $value
     * @param integer $ttl Time to live in seconds
     * @param integer $time Current timestamp
     * @return void
     */
    public function set($key, $value, $ttl, $time);

    /**
     * Checks whether cache key exists
     * 
     * @param string $key
     * @return boolean
     */
    public function has($key);

    /**
     * Removes a key
     * 
     * @param string $key
     * @return boolean
     */
    public function remove($key);

    /**
     * Returns cache's value by its key
     * 
     * @param string $key Target key
     * @param mixed $default Value to be returned in case key doesn't exist
     * @return mixed
     */
    public function getValueByKey($key, $default);
}

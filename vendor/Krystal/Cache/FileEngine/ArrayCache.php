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

/* An abstraction over cache's array */
final class ArrayCache implements ArrayCacheInterface
{
    const CACHE_PARAM_VALUE = 'value';
    const CACHE_PARAM_TTL = 'ttl';
    const CACHE_PARAM_CREATED = 'created';

    /**
     * Cache data
     * 
     * @var array
     */
    private $data = array();

    /**
     * Decrements a value
     * 
     * @param string $key
     * @param integer $step
     * @return void
     */
    public function decrement($key, $step)
    {
        $value = $this->getValueByKey($key, false);
        $this->alter($key, $value - $step);
    }

    /**
     * Increments a value
     * 
     * @param string $key
     * @param integer $step
     * @return void
     */
    public function increment($key, $step)
    {
        $value = $this->getValueByKey($key, false);
        $this->alter($key, $value + $step);
    }

    /**
     * Alters key's value
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    private function alter($key, $value)
    {
        if ($this->has($key)) {
            $this->data[$key][self::CACHE_PARAM_VALUE] = $value;
        }
    }

    /**
     * Clears the data
     * 
     * @return void
     */
    public function clear()
    {
        $this->data = array();
    }

    /**
     * Sets the cache data
     * 
     * @param array $data
     * @return void
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * Returns cache data
     * 
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Returns cache data as key => value pair
     * 
     * @return array
     */
    public function getAsPair()
    {
        $result = array();

        foreach ($this->data as $key => $options) {
            $result[$key] = $options[self::CACHE_PARAM_VALUE];
        }

        return $result;
    }

    /**
     * Checks whether cache key is expired
     * 
     * @param string $key
     * @param integer $time Current timestamp
     * @return boolean
     */
    public function isExpired($key, $time)
    {
        return $this->getCreatedTime($key) + $this->getTtl($key) < $time;
    }

    /**
     * Sets the cache data
     * 
     * @param string $key
     * @param mixed $value
     * @param integer $ttl Time to live in seconds
     * @param integer $time Current timestamp
     * @return void
     */
    public function set($key, $value, $ttl, $time)
    {
        $this->data[$key] = array(
            self::CACHE_PARAM_VALUE => $value,
            self::CACHE_PARAM_CREATED => $time,
            self::CACHE_PARAM_TTL => $ttl
        );
    }

    /**
     * Checks whether cache key exists
     * 
     * @param string $key
     * @return boolean
     */
    public function has($key)
    {
        return isset($this->data[$key]) && is_array($this->data[$key]);
    }

    /**
     * Removes a key
     * 
     * @param string $key
     * @return boolean
     */
    public function remove($key)
    {
        if ($this->has($key)) {
            unset($this->data[$key]);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns cache's value by its key
     * 
     * @param string $key Target key
     * @param mixed $default Value to be returned in case key doesn't exist
     * @return mixed
     */
    public function getValueByKey($key, $default)
    {
        return $this->get($key, self::CACHE_PARAM_VALUE, $default);
    }

    /**
     * Returns ttl of particular key
     * 
     * @param string $key
     * @return integer
     */
    private function getTtl($key)
    {
        return $this->get($key, self::CACHE_PARAM_TTL, false);
    }

    /**
     * Returns timestamp of creating for particular cache key
     * 
     * @param string $key
     * @return integer
     */
    private function getCreatedTime($key)
    {
        return $this->get($key, self::CACHE_PARAM_CREATED, false);
    }

    /**
     * Returns data by associated key
     * 
     * @param string $key
     * @param string $value
     * @param mixed $default Value to be returned in case key doesn't exist
     * @return mixed
     */
    private function get($key, $value, $default)
    {
        if ($this->has($key)) {
            return $this->data[$key][$value];
        } else {
            return $default;
        }
    }
}

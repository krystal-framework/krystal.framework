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

use Krystal\Cache\CacheEngineInterface;

/**
 * MySQL PDO cache storage. The table must be created before using it
 */
final class SqlCacheEngine implements CacheEngineInterface
{
    /**
     * Any-compliant cache mapper
     * 
     * @var \Krystal\Cache\Sql\CacheMapperInterface
     */
    private $cacheMapper;

    /**
     * Cache data
     * 
     * @var array
     */
    private $cache = array();

    /**
     * Tells whether service is already initialized
     * 
     * @var boolean
     */
    private $initialized = false;

    /**
     * State initialization
     * 
     * @param \Krystal\Cache\Sql\CacheMapperInterface $cacheMapper
     * @return void
     */
    public function __construct(CacheMapperInterface $cacheMapper)
    {
        $this->cacheMapper = $cacheMapper;
    }

    /**
     * Initializes the service on demand
     * 
     * @return void
     */
    private function initializeOnDemand()
    {
        if ($this->initialized === false) {

            $this->initialized = true;
            $this->cache = $this->cacheMapper->fetchAll();

            // Run garbage collection on each HTTP request
            $this->gc();
        }
    }

    /**
     * Flushes the cache
     * 
     * @return boolean
     */
    public function flush()
    {
        $this->initializeOnDemand();

        if ($this->cacheMapper->flush()) {

            // Reset the current state as well
            $this->cache = array();
            return true;

        } else {
            return false;
        }
    }

    /**
     * Increments a value
     * 
     * @param string $key
     * @param integer $step
     * @return boolean
     */
    public function increment($key, $step = 1)
    {
        $this->initializeOnDemand();

        if ($this->cacheMapper->increment($key, $step)) {

            // Synchronize with the current state
            $value = $this->get($key);
            $this->set($key, $value + $step, $this->getKeyTtl($key));

            return true;

        } else {
            return false;
        }
    }

    /**
     * Decrements a value
     * 
     * @param string $key
     * @param integer $step
     * @return boolean
     */
    public function decrement($key, $step = 1)
    {
        $this->initializeOnDemand();

        if ($this->cacheMapper->decrement($key, $step)) {

            // Synchronize with the current state
            $value = $this->get($key);
            $this->set($key, $value - $step, $this->getKeyTtl($key));

            return true;

        } else {
            return false;
        }
    }

    /**
     * Garbage collection
     * 
     * @return boolean False if nothing is done, True otherwise
     */
    private function gc()
    {
        foreach (array_keys($this->cache) as $key) {
            if ($this->isOutdated($key, time())) {
                $this->remove($key);
            }
        }
    }

    /**
     * Check whether key is outdated
     * 
     * @param string $key
     * @param integer $time Current timestamp
     * @return boolean
     */
    private function isOutdated($key, $time)
    {
        return ($this->getTouchByKey($key) + $this->getKeyTtl($key)) < $time;
    }

    /**
     * Fetch touch date of the given key
     * 
     * @param string $key
     * @return integer
     */
    private function getTouchByKey($key)
    {
        return (int) $this->cache[$key][ConstProviderInterface::CACHE_PARAM_CREATED_ON];
    }

    /**
     * Fetches time to live by its associated key
     * 
     * @param string $key
     * @return integer
     */
    private function getKeyTtl($key)
    {
        return (int) $this->cache[$key][ConstProviderInterface::CACHE_PARAM_TTL];
    }

    /**
     * Write data to the cache
     * 
     * @param string $key
     * @param mixed $value
     * @param integer $ttl The lifetime in seconds
     * @return boolean
     */
    public function set($key, $value, $ttl)
    {
        $this->initializeOnDemand();

        if (!is_string($key)) {
            throw new InvalidArgumentException(sprintf('Argument #1 must be a string and only, received "%s"', gettype($key)));
        }

        $time = time();

        if ($this->has($key)) {
            $this->cacheMapper->update($key, $value, $ttl);
        } else {
            $this->cacheMapper->insert($key, $value, $ttl, $time);
        }

        // For the current request
        $this->cache[$key] = array(
            ConstProviderInterface::CACHE_PARAM_VALUE => $value,
            ConstProviderInterface::CACHE_PARAM_CREATED_ON => $time,
            ConstProviderInterface::CACHE_PARAM_TTL => $ttl
        );

        return true;
    }

    /**
     * Returns cache data
     * 
     * @return array
     */
    public function getAll()
    {
        $this->initializeOnDemand();

        $result = array();

        foreach ($this->cache as $key => $options) {
            $result[$key] = $options[ConstProviderInterface::CACHE_PARAM_VALUE];
        }

        return $result;
    }

    /**
     * Check whether cache item exists
     * 
     * @param string $key
     * @return boolean
     */
    public function has($key)
    {
        $this->initializeOnDemand();
        return in_array($key, array_keys($this->cache));
    }

    /**
     * Reads cache item by its key
     * 
     * @param string $key
     * @param mixed $default Default value to be returned in $key doesn't exist
     * @return mixed
     */
    public function get($key, $default = false)
    {
        $this->initializeOnDemand();

        if ($this->has($key)) {
            return $this->cache[$key][ConstProviderInterface::CACHE_PARAM_VALUE];
        } else {
            return $default;
        }
    }

    /**
     * Removes an entry from the cache
     * 
     * @param string $key
     * @return boolean
     */
    public function remove($key)
    {
        $this->initializeOnDemand();

        if ($this->has($key) && $this->cacheMapper->delete($key)) {

            unset($this->cache[$key]);
            return true;

        } else {
            return false;
        }
    }
}

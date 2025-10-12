<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Cache;

use Closure;

/**
 * A wrapper around a cache engine that provides namespace-based key management for cached data.
 * It ensures that all cache keys are prefixed with a specific namespace, allowing for:
 * 
 * Key Isolation: Avoids conflicts by grouping cache keys under a namespace.
 * Efficient Cache Management: Enables batch operations like namespace-based cache flushing.
 * Encapsulation & Reusability: Abstracts cache operations while enforcing a consistent key format.
 */
final class CacheNamespace implements CacheEngineInterface
{
    /**
     * Target namespace
     * 
     * @var string
     */
    private $namespace;

    /**
     * Cache engine instance
     *
     * @var \Krystal\Cache\CacheEngineInterface
     */
    private $engine;

    /**
     * State initialization
     * 
     * @param \Krystal\Cache\CacheEngineInterface $engine
     * @param string $namespace
     * @return void
     */
    public function __construct(CacheEngineInterface $engine, $namespace)
    {
        $this->engine = $engine;
        $this->namespace = $namespace;
    }

    /**
     * Converts a key
     * 
     * @param string $key
     * @return string
     */
    private function convertKey($key)
    {
        return $this->getSeparator() . $key;
    }

    /**
     * Returns prepared separator
     * 
     * @return string
     */
    private function getSeparator()
    {
        return $this->namespace . '_____';
    }

    /**
     * Increments numeric value
     * 
     * @param string $key
     * @param integer $step
     * @return boolean
     */
    public function increment($key, $step = 1)
    {
        return $this->engine->increment($this->convertKey($key), $step);
    }

    /**
     * Decrements numeric value
     * 
     * @param string $key
     * @param integer $step
     * @return boolean
     */
    public function decrement($key, $step = 1)
    {
        return $this->engine->decrement($this->convertKey($key), $step);
    }

    /**
     * Stores the data to the cache
     * 
     * @param string $key
     * @param mixed $value
     * @param integer $ttl Time to live in seconds
     * @return boolean
     */
    public function set($key, $value, $ttl)
    {
        return $this->engine->set($this->convertKey($key), $value, $ttl);
    }

    /**
     * Reads key's value from a cache if present
     * 
     * @param string $key
     * @param mixed $default Default value if key doesn't exist
     * @return mixed
     */
    public function get($key, $default = false)
    {
        return $this->engine->get($this->convertKey($key), $default);
    }

    /**
     * Returns value from a cache by key.
     * Only calls the callback when the cache key is missing
     * 
     * @param string $key
     * @param \Closure Callback function that returns if key is missing
     * @param integer $ttl Time to live in seconds
     * @return mixed
     */
    public function getOnce($key, Closure $callback, $ttl)
    {
        return $this->engine->getOnce($this->convertKey($key), $callback, $ttl);
    }

    /**
     * Removes a key from cache
     * 
     * @param string $key
     * @return boolean True if removed, false if could not
     */
    public function remove($key)
    {
        return $this->engine->remove($this->convertKey($key));
    }

    /**
     * Check whether key exists in cache
     * 
     * @param string $key
     * @return boolean True if exists, false otherwise
     */
    public function has($key)
    {
        return $this->engine->has($this->convertKey($key));
    }

    /**
     * Returns all cache data
     * 
     * @return array
     */
    public function getAll()
    {
        $items = $this->engine->getAll();
        $prefix = $this->getSeparator();
        $prefixLength = strlen($prefix);
        $output = [];

        foreach ($items as $key => $value) {
            if (strncmp($key, $prefix, $prefixLength) === 0) {
                $output[substr($key, $prefixLength)] = $value;
            }
        }

        return $output;
    }

    /**
     * Flush the cache
     * 
     * @return boolean Depending on success
     */
    public function flush()
    {
        $items = $this->getAll();

        if (empty($items)) {
            return false;
        }

        foreach ($items as $key => $value) {
            $this->remove($key);
        }

        return true;
    }
}

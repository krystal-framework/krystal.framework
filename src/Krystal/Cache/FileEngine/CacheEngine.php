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

use Krystal\Cache\CacheTrait;
use Krystal\Cache\CacheEngineInterface;

final class CacheEngine implements CacheEngineInterface
{
    use CacheTrait;

    /**
     * An abstraction over cache's array
     * 
     * @var \Krystal\Cache\FileEngine\ArrayCacheInterface
     */
    private $arrayCache;

    /**
     * Array signature keeper
     * 
     * @var \Krystal\Cache\FileEngine\ArraySignatureInterface
     */
    private $arraySignature;

    /**
     * File storage abstraction
     * 
     * @var \Krystal\Cache\FileEngine\CacheFileInterface
     */
    private $storage;

    /**
     * State initialization
     * 
     * @param \Krystal\Cache\FileEngine\ArrayCacheInterface $arrayCache
     * @param \Krystal\Cache\FileEngine\ArraySignatureInterface $arraySignature
     * @param \Krystal\Cache\FileEngine\CacheFileInterface $storage
     * @return void
     */
    public function __construct(ArrayCacheInterface $arrayCache, ArraySignatureInterface $arraySignature, CacheFileInterface $storage)
    {
        $this->arrayCache = $arrayCache;
        $this->arraySignature = $arraySignature;
        $this->storage = $storage;
    }

    /**
     * Saves on destruction
     * 
     * @return void
     */
    public function __destruct()
    {
        return $this->save();
    }

    /**
     * Initializes the engine
     * Should be called right after state initialization
     * 
     * @return void
     */
    public function initialize()
    {
        // Load data from a file file
        $this->storage->load();
        $data = $this->storage->getContent();

        // Set initial array signature
        $this->arraySignature->setData($data);
        $this->arrayCache->setData($data);

        // Run garbage collection
        $this->gc();
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
        return $this->arrayCache->increment($key, $step);
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
        return $this->arrayCache->decrement($key, $step);
    }

    /**
     * Flushes the cache
     * 
     * @return void
     */
    public function flush()
    {
        return $this->arrayCache->clear();
    }

    /**
     * Returns all cache data
     * 
     * @return array
     */
    public function getAll()
    {
        return $this->arrayCache->getAsPair();
    }

    /**
     * Saves cache data if there's at least one change
     * 
     * @return boolean
     */
    private function save()
    {
        $data = $this->arrayCache->getData();

        // Do save in case we have at least one change in configuration
        if ($this->arraySignature->hasChanged($data)) {
            if (!$this->write()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Garbage collection
     * 
     * @return void
     */
    private function gc()
    {
        // Whether to refresh the data
        $refresh = false;

        foreach (array_keys($this->arrayCache->getData()) as $key) {
            // Do remove outdated keys from the collection
            if ($this->arrayCache->isExpired($key, time())) {
                $refresh = true;
                $this->arrayCache->remove($key);
            }
        }

        if ($refresh === true) {
            // Explicit save with fresh data
            $this->write();
        }
    }

    /**
     * Writes data to the file
     * 
     * @return boolean
     */
    private function write()
    {
        $data = $this->arrayCache->getData();
        return $this->storage->save($data);
    }

    /**
     * Returns a value from the cache's storage
     * 
     * @param string $key
     * @param mixed $default Default value to be returned if cache key doesn't exist
     * @return mixed
     */
    public function get($key, $default = false)
    {
        return $this->arrayCache->getValueByKey($key, $default);
    }

    /**
     * Stores a value to cache
     * 
     * @param string $key
     * @param mixed $value
     * @param integer $ttl Time to live in seconds
     * @return void
     */
    public function set($key, $value, $ttl)
    {
        $this->arrayCache->set($key, $value, $ttl, time());
        return $this;
    }

    /**
     * Checks whether cache's key exists
     * 
     * @param string $key
     * @return boolean
     */
    public function has($key)
    {
        return $this->arrayCache->has($key);
    }

    /**
     * Removes a key from cache's storage
     * 
     * @param string $key
     * @return boolean
     */
    public function remove($key)
    {
        return $this->arrayCache->remove($key);
    }
}

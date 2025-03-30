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
use RuntimeException;

/** 
 * Windows Cache Extension for PHP is a PHP accelerator that is used to increase the speed of PHP applications 
 * running on Windows and Windows Server. 
 * Once the Windows Cache Extension for PHP is enabled and loaded by the PHP engine, 
 * PHP applications can take advantage of the functionality without any code modifications.
 * 
 * @see http://pecl.php.net/package/wincache
 */
final class WinCache implements CacheEngineInterface
{
    use CacheTrait;

    /**
     * Returns all cache data
     * 
     * @return array
     */
    public function getAll()
    {
        throw new BadMethodCallException('Method getAll() is not supported in WinCache engine');
    }

    /**
     * Refreshes the cache entries for the files, whose names were passed in the input argument.  
     * If no argument is specified then refreshes all the entries in the cache.
     * 
     * @param array $files An array of file names for files that need to be refreshed. An absolute or relative file paths can be used. 
     * @return boolean Returns TRUE on success or FALSE on failure. 
     */
    public function refreshIfChanged(array $files = array())
    {
        return wincache_refresh_if_changed($files);
    }

    /**
     * Increments the value associated with the key
     * 
     * @param string $key The key that was used to store the variable in the cache.
     * @param integer $step The value by which the variable associated with the key will get incremented
     * @return mixed Returns the incremented value on success and FALSE on failure. 
     */
    public function increment($key, $step = 1)
    {
        return wincache_ucache_inc($key, $step);
    }

    /**
     * Decrements the value associated with the key 
     * 
     * @param string $key The key that was used to store the variable in the cache.
     * @param integer $step The value by which the variable associated with the key will get decremented.
     * @return mixed Returns the decremented value on success and FALSE on failure. 
     */
    public function decrement($key, $step = 1)
    {
        return wincache_ucache_dec($key, $step);
    }

    /**
     * Releases an exclusive lock on a given key 
     * 
     * @param string $key
     * @return boolean Depending on success
     */
    public function unlock($key)
    {
        return wincache_unlock($key);
    }

    /**
     * Acquires an exclusive lock on a given key 
     * 
     * @param string $key Name of the key in the cache to get the lock on. 
     * @param boolean $is_global Controls whether the scope of the lock is system-wide or local. 
     * @return boolean Depending on success
     */
    public function lock($key, $is_global = false)
    {
        return wincache_lock($key, $is_global);
    }

    /**
     * Compares the variable with old value and assigns new value to it 
     * 
     * @param string $key The key that is used to store the variable in the cache
     * @param integer $old_value Old value of the variable pointed by key in the user cache. 
     * @param integer $new_value New value which will get assigned to variable pointer by key if a match is found. 
     * @return boolean
     */
    public function cas($key, $old_value, $new_value)
    {
        return wincache_ucache_cas($key, $old_value, $new_value);
    }

    /**
     * Deletes entire content of the user cache
     * 
     * @return void
     */
    public function flush()
    {
        return wincache_ucache_clear();
    }

    /**
     * Writes data to cache
     * 
     * @param string $key
     * @param mixed $value
     * @param integer $ttl
     * @return boolean Depending on success
     */
    public function set($key, $value, $ttl)
    {
        return wincache_ucache_set($key, $value, $ttl);
    }

    /**
     * Check whether key exists in the storage
     * 
     * @param string $key
     * @return boolean
     */
    public function has($key)
    {
        return wincache_ucache_exists($key);
    }

    /**
     * Reads key from a cache
     * 
     * @param string $key
     * @param boolean $default Default value to be returned if required key doesn't exist
     * @return mixed
     */
    public function get($key, $default = false)
    {
        if ($this->has($key)) {
            return wincache_ucache_get($key);
        } else {
            return $default;
        }
    }

    /**
     * Deletes data associated with a key
     * 
     * @param string $key
     * @throws \RuntimeException if attempted to delete non-existing key
     * @return boolean Depending on success
     */
    public function remove($key)
    {
        if ($this->has($key)) {
            return wincache_ucache_delete($key);
        } else {
            throw new RuntimeException(sprintf(
                'Attempted to delete non-existing key "%s"', $key
            ));
        }
    }

    /**
     * Retrieves information about user cache memory usage 
     * 
     * @return array
     */
    public function getInfo()
    {
        return array(
            'ucache_meminfo' => wincache_ucache_meminfo(),
            'ucache_info' =>    wincache_ucache_info(),
            'session_cache_info' => wincache_scache_info(),
            'session_cache_meminfo' =>  wincache_scache_meminfo(),
            'rp_meminfo' => wincache_rplist_meminfo(),
            'rp_fileinfo' =>    wincache_rplist_fileinfo(),
            'opcode_fileinfo' =>    wincache_ocache_fileinfo(),
            'opcode_meminfo' => wincache_ocache_meminfo(),
            'filecache_meminfo' =>  wincache_fcache_fileinfo(),
            'filecache_fileinfo' => wincache_fcache_meminfo()
        );
    }
}

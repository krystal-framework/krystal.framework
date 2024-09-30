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
 * The Alternative PHP Cache (APC) is a free and open opcode cache for PHP.
 * Its goal is to provide a free, open, and robust framework for caching and optimizing PHP intermediate code.
 * 
 * Method documentations comes from the original PHP manual
 */
final class APC implements CacheEngineInterface
{
    /**
     * Returns all cache data
     * 
     * @return array
     */
    public function getAll()
    {
        throw new BadMethodCallException('The method getAll() is not available in APC');
    }

    /**
     * Returns APC storage info
     * 
     * @return array
     */
    public function getInfo()
    {
        return array(
            'sma_info' => apc_sma_info(), 
            'cache_info' => apc_cache_info(),
        );
    }

    /**
     * Loads a set of constants from the cache 
     * 
     * @param string $key The name of the constant set
     * @param boolean $case_sensitive The default behaviour for constants is to be declared case-sensitive
     * @return boolean Depending on success
     */
    public function loadConstants($key, $case_sensetive = true)
    {
        return apc_load_constants($key, $case_sensetive);
    }

    /**
     * Defines a set of constants for retrieval and mass-definition 
     * 
     * @param string $key The key serves as the name of the constant set being stored. 
     * @param array $constants An associative array of constant_name => value pairs. 
     * @param boolean $case_sensetive The default behaviour for constants is to be declared case-sensitive
     * @return boolean Depending on success
     */
    public function defineConstants($key, array $constants, $case_sensetive = true)
    {
        return apc_define_constants($key, $constants, $case_sensetive);
    }

    /**
     * Deletes files from the opcode cache
     * 
     * @param mixed $keys The files to be deleted. Accepts a string, array of strings, or an APCIterator object.
     * @return boolean Depending on success
     */
    public function deleteFile($keys)
    {
        return apc_delete_file($keys);
    }

    /**
     * Stores a file in the bytecode cache, bypassing all filters. 
     * 
     * @param string $filename Full or relative path to a PHP file that will be compiled and stored in the bytecode cache. 
     * @param boolean $atomic 
     * @return boolean Depending on success
     */
    public function compileFile($filename, $atomic)
    {
        return apc_compile_file($filename, $atomic);
    }

    /**
     * Load a binary dump from a file into the APC file/user cache
     * 
     * @param string $filename The file name containing the dump, likely from apc_bin_dumpfile()
     * @param resource $context The files context. 
     * @param integer $flags Either APC_BIN_VERIFY_CRC32, APC_BIN_VERIFY_MD5, or both. 
     * @return boolean Depending on success
     */
    public function binLoadFile($filename, $context = null, $flags = \APC_BIN_VERIFY_CRC32)
    {
        return apc_bin_loadfile($filename, $contenxt, $flags);
    }

    /**
     * Load a binary dump into the APC file/user cache
     * 
     * @param string $data The binary dump being loaded, likely from apc_bin_dump(). 
     * @param integer $flags Either APC_BIN_VERIFY_CRC32, APC_BIN_VERIFY_MD5, or both. 
     * @return Returns TRUE if the binary dump data was loaded with success, otherwise FALSE is returned.
     */
    public function binLoad($data, $flags = 0)
    {
        return apc_bin_load($data, $flags);
    }

    /**
     * Get a binary dump of the given files and user variables
     * 
     * @param array $files The files. Passing in NULL signals a dump of every entry, while passing in array() will dump nothing. 
     * @param array $user_vars The user vars. Passing in NULL signals a dump of every entry, while passing in array() will dump nothing. 
     * @return string Returns a binary dump of the given files and user variables from the APC cache,  
     *                FALSE if APC is not enabled, or NULL if an unknown error is encountered.
     */
    public function binDump(array $files, $user_vars = array())
    {
        return apc_bin_dump($files, $user_vars);
    }

    /**
     * Output a binary dump of cached files and user variables to a file
     * 
     * @param array $files The file names being dumped
     * @param array $user_vars The user variables being dumped
     * @param string $filename The filename where the dump is being saved. 
     * @param integer $flags Flags passed to the filename stream
     * @param resource $context The context passed to the filename stream
     * @return mixed The number of bytes written to the file, otherwise FALSE 
     */
    public function binDumpFile(array $files, array $user_vars, $filename, $flags = 0, $context = null)
    {
        return apc_bin_dumpfile($files, $user_vars, $filename, $flags, $context);
    }

    /**
     * Updates an old value with a new value
     * 
     * @param string $key The key of the value being updated. 
     * @param integer $old The old value (the value currently stored).
     * @param integer $new The new value to update to. 
     * @return boolean Depending on success
     */
    public function cas($key, $old, $new)
    {
        return apc_cas($key, $old, $new);
    }

    /**
     * Increase a stored number
     * 
     * @param string $key The key of the value being increased. 
     * @param integer $step The step, or value to increase. 
     * @return boolean Returns the current value of key's value on success, or FALSE on failure 
     */
    public function increment($key, $step = 1)
    {
        return apc_inc($key, $step);
    }

    /**
     * Decrease a stored number
     * 
     * @param string $key The key of the value being decreased. 
     * @param integer $step The step, or value to decrease. 
     * @return mixed Returns the current value of key's value on success, or FALSE on failure
     */
    public function decrement($key, $step = 1)
    {
        return apc_dec($key, $step);
    }

    /**
     * Cache a variable in the data store 
     * 
     * @param string $key
     * @param mixed $value
     * @param integer $ttl
     * @return boolean
     */
    public function set($key, $value, $ttl)
    {
        return apc_store($key, $value, $ttl);
    }

    /**
     * Removes a stored variable from the cache 
     * 
     * @param string $key The key to be removed
     * @return boolean Depending on success
     */
    public function remove($key)
    {
        return apc_delete($key);
    }

    /**
     * Reads a value associate with given value from APC storage
     * 
     * @param string $key
     * @param boolean $default
     * @return mixed
     */
    public function get($key, $default = false)
    {
        if ($this->exists($key) !== false) {
            return apc_fetch($key);
        } else {
            return $default;
        }
    }

    /**
     * Whether key exists
     * 
     * @param string $key
     * @return boolean
     */
    public function has($key)
    {
        return apc_exists($key);
    }

    /**
     * Removes all data from the APC
     * 
     * @param string $type If cache_type is "user", the user cache will be cleared; 
     *                     otherwise, the system cache (cached files) will be cleared. 
     * @return boolean
     */
    public function flush()
    {
        return call_user_func_array('apc_clear_cache', func_get_args());
    }
}

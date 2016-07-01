<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Config;

interface ConfigModuleServiceInterface
{
    /**
     * Store many items at once
     * 
     * @param array $vars
     * @return boolean
     */
    public function storeMany(array $vars);

    /**
     * Stores an item
     * 
     * @param string $key
     * @param string $value
     * @return boolean
     */
    public function store($key, $value);

    /**
     * Returns a value
     * 
     * @param string $key
     * @param mixed $default Default value to be returned if key doesn't exist
     * @return mixed
     */
    public function get($key, $default = false);

    /**
     * Returns all configuration
     * 
     * @return array|object
     */
    public function getAll();

    /**
     * Checks whether configuration key exists
     * 
     * @param string $key
     * @return boolean
     */
    public function has($key);

    /**
     * Checks whether many configuration keys exists at once
     * 
     * @param array $keys
     * @return boolean
     */
    public function hasMany(array $keys);

    /**
     * Removes a key
     * 
     * @param string $key
     * @return boolean
     */
    public function remove($key);

    /**
     * Removes all data associated with the module
     * 
     * @return boolean
     */
    public function removeAll();
}

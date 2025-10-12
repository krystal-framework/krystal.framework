<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Config;

class ConfigModuleService implements ConfigModuleServiceInterface
{
    /**
     * Module name
     * 
     * @var string
     */
    private $module;

    /**
     * Configuration service
     * 
     * @var \Krystal\Config\ConfigServiceInterface
     */
    private $configService;

    /**
     * State initialization
     * 
     * @param string $module
     * @paran \Krystal\Config\ConfigServiceInterface $configService
     * @return void
     */
    public function __construct($module, ConfigServiceInterface $configService)
    {
        $this->module = $module;
        $this->configService = $configService;
    }

    /**
     * Store many items at once
     * 
     * @param array $vars
     * @return boolean
     */
    public function storeMany(array $vars)
    {
        return $this->configService->storeModule($this->module, $vars);
    }

    /**
     * Stores an item
     * 
     * @param string $key
     * @param string $value
     * @return boolean
     */
    public function store($key, $value)
    {
        return $this->configService->store($this->module, $key, $value);
    }

    /**
     * Returns a value
     * 
     * @param string $key
     * @param mixed $default Default value to be returned if key doesn't exist
     * @return mixed
     */
    public function get($key, $default = false)
    {
        return $this->configService->get($this->module, $key, $default);
    }

    /**
     * Returns all configuration
     * 
     * @return array|object
     */
    public function getAll()
    {
        return $this->getAllByModule($this->module);
    }

    /**
     * Checks whether configuration key exists
     * 
     * @param string $key
     * @return boolean
     */
    public function has($key)
    {
        return $this->configService->has($this->module, $key);
    }

    /**
     * Checks whether many configuration keys exists at once
     * 
     * @param array $keys
     * @return boolean
     */
    public function hasMany(array $keys)
    {
        return $this->configService->hasMany($this->module, $keys);
    }

    /**
     * Removes a key
     * 
     * @param string $key
     * @return boolean
     */
    public function remove($key)
    {
        return $this->configService->remove($this->module, $key);
    }

    /**
     * Removes all data associated with the module
     * 
     * @return boolean
     */
    public function removeAll()
    {
        return $this->configService->removeAllByModule($this->module);
    }
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Config\Sql;

use Krystal\Config\ConfigServiceInterface;

final class SqlConfigService implements ConfigServiceInterface
{
    /**
     * Any compliant configuration mapper
     * 
     * @var \Krystal\Config\Sql\ConfigMapperInterface
     */
    private $configMapper;

    /**
     * An abstraction over configuration's array
     * 
     * @var \Krystal\Config\Sql\ArrayConfigInterface
     */
    private $arrayConfig;

    /**
     * Tells whether service is already initialized
     * 
     * @var boolean
     */
    private $initialized = false;

    /**
     * State initialization
     * 
     * @param \Krystal\Config\Sql\ConfigMapperInterface $configMapper
     * @param \Krystal\Config\Sql\ArrayConfigInterface $arrayConfig
     * @return void
     */
    public function __construct(ConfigMapperInterface $configMapper, ArrayConfigInterface $arrayConfig)
    {
        $this->configMapper = $configMapper;
        $this->arrayConfig = $arrayConfig;
    }

    /**
     * Initializes the service on demand
     * 
     * @return void
     */
    private function initializeOnDemand()
    {
        if ($this->initialized === false) {
            $this->arrayConfig->setData($this->configMapper->fetchAll());
            $this->initialized = true;
        }
    }

    /**
     * Stores configuration's entry
     * 
     * @param string $module
     * @param string $name
     * @param string $value
     * @return boolean
     */
    public function store($module, $name, $value)
    {
        $this->initializeOnDemand();

        if (!$this->has($module, $name)) {
            if ($this->configMapper->insert($module, $name, $value)) {
                $this->arrayConfig->add($module, $name, $value);
                return true;
            }

        } else {
            if ($this->configMapper->update($module, $name, $value)) {
                $this->arrayConfig->update($module, $name, $value);
                return true;
            }
        }

        // By default
        return false;
    }

    /**
     * Returns all configuration entries by associated module
     * 
     * @param string $module
     * @return array|boolean
     */
    public function getAllByModule($module)
    {
        $this->initializeOnDemand();
        return $this->arrayConfig->getAllByModule($module);
    }

    /**
     * Returns configuration entry from the cache
     * 
     * @param string $module
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get($module, $name, $default = false)
    {
        $this->initializeOnDemand();
        return $this->arrayConfig->get($module, $name, $default);
    }

    /**
     * Checks configuration's entry exists in a module
     * 
     * @param string $module
     * @param string $name
     * @return boolean
     */
    public function has($module, $name)
    {
        $this->initializeOnDemand();
        return $this->arrayConfig->has($module, $name);
    }

    /**
     * Removes all configuration
     * 
     * @return boolean
     */
    public function removeAll()
    {
        $this->initializeOnDemand();

        if ($this->configMapper->truncate()) {
            $this->arrayConfig->clear();
            return true;

        } else {
            return false;
        }
    }

    /**
     * Removes a configuration's name and value by associated module
     * 
     * @param string $module
     * @param string $name
     * @return boolean
     */
    public function remove($module, $name)
    {
        $this->initializeOnDemand();

        if ($this->exists($module, $name) && $this->configMapper->delete($module, $name)) {
            $this->arrayConfig->remove($module, $name);
            return true;

        } else {
            return false;
        }
    }

    /**
     * Removes all configuration data by associated module
     * 
     * @param string $module
     * @return boolean
     */
    public function removeAllByModule($module)
    {
        $this->initializeOnDemand();

        if ($this->arrayConfig->hasModule($module) && $this->configMapper->deleteAllByModule($module)) {
            $this->arrayConfig->removeAllByModule($module);
            return true;
        } else {
            return false;
        }
    }
}

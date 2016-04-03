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

/* An abstraction over configuration array */
final class ArrayConfig implements ArrayConfigInterface
{
    /**
     * Configuration data
     * 
     * @var array
     */
    private $data = array();

    /**
     * Sets configuration data
     * 
     * @param array $data
     * @return void
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * Removes all configuration data
     * 
     * @return void
     */
    public function clear()
    {
        $this->data = array();
    }

    /**
     * Removes by module and its associated name
     * 
     * @param string $module
     * @param string $name
     * @return void
     */
    public function remove($module, $name)
    {
        $index = 0;

        if ($this->has($module, $name, $index)) {
            unset($this->data[$index]);
        }
    }

    /**
     * Removes all by associated module
     * 
     * @param string $module
     * @return void
     */
    public function removeAllByModule($module)
    {
        foreach ($this->getIndexesByModule($module) as $index) {
            if (isset($this->data[$index])) {
                unset($this->data[$index]);
            }
        }
    }

    /**
     * Returns indexes by associated module
     * 
     * @param string $module
     * @return array
     */
    private function getIndexesByModule($module)
    {
        // Indexes to be removed
        $indexes = array();

        foreach ($this->data as $index => $row) {
            if (isset($row[ConstProviderInterface::CONFIG_PARAM_MODULE]) && $row[ConstProviderInterface::CONFIG_PARAM_MODULE] == $module) {
                array_push($indexes, $index);
            }
        }

        return $indexes;
    }

    /**
     * Returns all data by associated module
     * 
     * @param string $module
     * @return array|boolean
     */
    public function getAllByModule($module)
    {
        if (!$this->hasModule($module)) {
            return false;
        } else {
            $result = array();

            foreach ($this->data as $index => $row) {
                if (isset($row[ConstProviderInterface::CONFIG_PARAM_MODULE]) && $row[ConstProviderInterface::CONFIG_PARAM_MODULE] == $module) {
                    $name = $row[ConstProviderInterface::CONFIG_PARAM_NAME];
                    $value = $row[ConstProviderInterface::CONFIG_PARAM_VALUE];

                    $result[$name] = $value;
                }
            }

            return $result;
        }
    }

    /**
     * Returns configuration entry
     * 
     * @param string $module
     * @param string $name
     * @param mixed $default Default value to be returned in case requested one doesn't exist
     * @return mixed
     */
    public function get($module, $name, $default)
    {
        $index = 0;

        if ($this->has($module, $name, $index)) {
            return $this->data[$index][ConstProviderInterface::CONFIG_PARAM_VALUE];
        } else {
            return $default;
        }
    }

    /**
     * Adds configuration data
     * 
     * @param string $module
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function add($module, $name, $value)
    {
        array_push($this->data, array(
            ConstProviderInterface::CONFIG_PARAM_MODULE => $module,
            ConstProviderInterface::CONFIG_PARAM_NAME => $name,
            ConstProviderInterface::CONFIG_PARAM_VALUE => $value
        ));
    }

    /**
     * Updates existing pair with new value
     * 
     * @param string $module
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function update($module, $name, $value)
    {
        foreach ($this->data as $index => $row) {
            if ($row[ConstProviderInterface::CONFIG_PARAM_MODULE] == $module && $row[ConstProviderInterface::CONFIG_PARAM_NAME] == $name) {
                // Alter found index's value
                $this->data[$index][ConstProviderInterface::CONFIG_PARAM_VALUE] = $value;
            }
        }
    }

    /**
     * Checks whether there's at least one module in the stack with provided name
     * 
     * @param string $module
     * @return boolean
     */
    public function hasModule($module)
    {
        foreach ($this->data as $index => $row) {
            if ($row[ConstProviderInterface::CONFIG_PARAM_MODULE] == $module) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks whether module has a specific key
     * 
     * @param string $module
     * @param string $name
     * @param integer $position For internal usage only
     * @return boolean
     */
    public function has($module, $name, &$position = false)
    {
        foreach ($this->data as $index => $row) {
            if ($row[ConstProviderInterface::CONFIG_PARAM_MODULE] == $module && $row[ConstProviderInterface::CONFIG_PARAM_NAME] == $name) {
                if ($position !== false) {
                    $position = $index;
                }

                return true;
            }
        }

        return false;
    }
}

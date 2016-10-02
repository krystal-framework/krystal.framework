<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Text;

/**
 * This is a simply utility to manage a key with its options
 */
final class CollectionManager implements CollectionManagerInterface
{
    /**
     * Data container
     * 
     * @var array
     */
    private $container = array();

    /**
     * State initialization
     * 
     * @param array $container Data container, can be optionally initialized
     * @return void
     */
    public function __construct(array $container = array())
    {
        if (!empty($container)) {
            $this->load($container);
        }
    }

    /**
     * Loads data from array
     * 
     * @param array $container
     * @return void
     */
    public function load(array $container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * Return options from the collection
     * 
     * @param string $filteringOption Can be optionally filtered by one option
     * @return array
     */
    public function getAllOptions($filteringOption = null)
    {
        if (is_null($filteringOption)) {
            return array_values($this->container);
        } else {
            $result = array();

            foreach ($this->container as $key => $options) {
                if (isset($options[$filteringOption])) {
                    $result[] = $options[$filteringOption];
                } else {
                    trigger_error('Attempted to filter by non-existing option', \E_USER_ERROR);
                }
            }

            return $result;
        }
    }

    /**
     * Returns current data container
     * 
     * @return array
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Returns amount of keys
     * 
     * @return integer
     */
    public function getKeysCount()
    {
        return count($this->container);
    }

    /**
     * Returns key's option
     * 
     * @param string $key
     * @param mixed $value
     * @param mixed $default Value to be returned if option doesn't exist
     * @return mixed
     */
    public function getWithOption($key, $option, $default = false)
    {
        if ($this->hasOption($key, $option)) {
            return $this->container[$key][$option];
        } else {
            return $default;
        }
    }

    /**
     * Checks whether provided key has an option
     * 
     * @param string $key
     * @param string $option
     * @return boolean
     */
    public function hasOption($key, $option)
    {
        return isset($this->container[$key]) && array_key_exists($option, $this->container[$key]);
    }

    /**
     * Adds key data with option and its value
     * 
     * @param string $key
     * @param string $option
     * @param string $value
     * @param boolean $append Whether to append target option if key already exists
     * @return void
     */
    public function addWithOption($key, $option, $value, $append = true)
    {
        if (!isset($this->container[$key])) {
            $this->container[$key] = array(
                $option => $value
            );

        } else {
            // Option already exists, so now depending on $append 
            if ($append === true) {
                $this->container[$key][$option] = $value;
            }
        }

        return $this;
    }

    /**
     * Updates an option by its associated key
     *
     * @param string $key Target key
     * @param string $option Option's name
     * @param mixed $value New value
     * @return void
     */
    public function updateWithOption($key, $option, $value)
    {
        if ($this->hasOption($key, $option)) {
            $this->container[$key][$option] = $value;
        }

        return $this;
    }

    /**
     * Removes an option by its associated key
     * 
     * @param string $key Target key
     * @param string $option Option to be removed
     * @return void
     */
    public function removeOptionByKey($key, $option)
    {
        if ($this->hasOption($key, $option)) {
            unset($this->container[$key][$option]);
        }

        return $this;
    }

    /**
     * Checks whether key already exists in the stack
     * 
     * @param string $key Key to be checked for existence
     * @return boolean
     */
    public function hasKey($key)
    {
        return isset($this->container[$key]);
    }

    /**
     * Removes all data by its associated key
     * 
     * @param string $key Key to be removed
     * @return boolean
     */
    public function removeKey($key)
    {
        if ($this->hasKey($key)) {
            unset($this->container[$key]);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Adds a key with its options
     * 
     * @param string $key
     * @param array $options
     * @return \Krystal\Text\CollectionManager
     */
    public function add($key, array $options)
    {
        $this->container[$key] = $options;
        return $this;
    }

    /**
     * Updates a key with new options
     * 
     * @param string $key
     * @param array $options
     * @return boolean
     */
    public function update($key, array $options)
    {
        $this->container[$key] = $options;
    }

    /**
     * Resets the container
     * 
     * @return void
     */
    public function reset()
    {
        $this->container = array();
    }
}

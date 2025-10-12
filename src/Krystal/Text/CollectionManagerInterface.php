<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Text;

interface CollectionManagerInterface
{
    /**
     * Loads data from array
     * 
     * @param array $container
     * @return void
     */
    public function load(array $container);

    /**
     * Return options from the collection
     * 
     * @param string $filteringOption Can be optionally filtered by one option
     * @return array
     */
    public function getAllOptions($filteringOption = null);

    /**
     * Returns a collection of keys
     * 
     * @return array
     */
    public function getKeys();

    /**
     * Returns current data container
     * 
     * @return array
     */
    public function getContainer();

    /**
     * Determines whether container is empty
     * 
     * @return boolean
     */
    public function isEmpty();

    /**
     * Returns amount of keys
     * 
     * @return integer
     */
    public function getKeysCount();

    /**
     * Returns key's option
     * 
     * @param string $key
     * @param mixed $value
     * @param mixed $default Value to be returned if option doesn't exist
     * @return mixed
     */
    public function getWithOption($key, $option, $default = false);

    /**
     * Checks whether provided key has an option
     * 
     * @param string $key
     * @param string $option
     * @return boolean
     */
    public function hasOption($key, $option);

    /**
     * Adds key data with option and its value
     * 
     * @param string $key
     * @param string $option
     * @param string $value
     * @param boolean $append Whether to append target option if key already exists
     * @return void
     */
    public function addWithOption($key, $option, $value, $append = true);

    /**
     * Updates an option by its associated key
     *
     * @param string $key Target key
     * @param string $option Option's name
     * @param mixed $value New value
     * @return void
     */
    public function updateWithOption($key, $option, $value);

    /**
     * Removes an option by its associated key
     * 
     * @param string $key Target key
     * @param string $option Option to be removed
     * @return void
     */
    public function removeOptionByKey($key, $option);

    /**
     * Checks whether key already exists in the stack
     * 
     * @param string $key Key to be checked for existence
     * @return boolean
     */
    public function hasKey($key);

    /**
     * Removes all data by its associated key
     * 
     * @param string $key Key to be removed
     * @return boolean
     */
    public function removeKey($key);

    /**
     * Adds a key with its options
     * 
     * @param string $key
     * @param array $options
     * @return \Krystal\Text\CollectionManager
     */
    public function add($key, array $options);

    /**
     * Updates a key with new options
     * 
     * @param string $key
     * @param array $options
     * @return boolean
     */
    public function update($key, array $options);

    /**
     * Resets the container
     * 
     * @return void
     */
    public function reset();
}

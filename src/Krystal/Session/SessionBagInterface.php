<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Session;

interface SessionBagInterface
{
    /**
     * Removes all data from session
     * 
     * @return boolean
     */
    public function removeAll();

    /**
     * Returns all session data
     * 
     * @return array
     */
    public function getAll();

    /**
     * Tells whether session is valid
     * 
     * @return boolean
     */
    public function isValid();

    /**
     * Starts a new session or continues existing one
     * 
     * @param array $options Cookie options
     * @return boolean
     */
    public function start(array $options = array());

    /**
     * Regenerates session's id
     * 
     * @return boolean
     */
    public function regenerate();

    /**
     * Defines session's name
     * 
     * @param string $name
     * @return void
     */
    public function setName($name);

    /**
     * Returns session's name
     * 
     * @return string
     */
    public function getName();

    /**
     * Checks whether session is started
     * 
     * @return boolean
     */
    public function isStarted();

    /**
     * Returns current session's id
     * 
     * @return string
     */
    public function getId();

    /**
     * Sets session id
     * 
     * @param string $id
     * @return void
     */
    public function setId($id);

    /**
     * Stores data in session's storage
     * 
     * @param string $key
     * @param mixed $value
     * @return \Krystal\Session\SessionBag
     */
    public function set($key, $value);

    /**
     * Set many keys at once
     * 
     * @param array $collection
     * @return \Krystal\Session\SessionBag
     */
    public function setMany(array $collection);

    /**
     * Determines whether session has a key
     * 
     * @param string $key
     * @return boolean
     */
    public function has($key);

    /**
     * Determines whether all keys present in the session storage
     * 
     * @param array $keys
     * @return boolean
     */
    public function hasMany(array $keys);

    /**
     * Reads data from a session
     * 
     * @param string $key
     * @param mixed $default Default value to be returned if request key doesn't exist
     * @return mixed
     */
    public function get($key, $default = false);

    /**
     * Returns flashed session key (will be removed after retrieval)
     * 
     * @param string $key
     * @throws \RuntimeException If attempted to read non-existing key
     * @return string
     */
    public function getFlashed($key);

    /**
     * Removes a key from the storage
     * 
     * @param string $key
     * @return boolean
     */
    public function remove($key);

    /**
     * Remove many session keys with their values at once
     * 
     * @param array $keys A collection of keys
     * @return boolean
     */
    public function removeMany(array $keys);

    /**
     * Free all session variables
     * 
     * @return void
     */
    public function free();

    /**
     * Returns true
     * if sessions are enabled, and one exists.  
     * 
     * @return boolean
     */
    public function isActive();

    /**
     * Checks if sessions are disabled
     * 
     * @return boolean
     */
    public function isDisabled();

    /**
     * Checks if sessions are enabled, but none exists 
     * 
     * @return boolean
     */
    public function isNone();

    /**
     * Returns cookie storage parameters for the session
     * 
     * @return array
     */
    public function getCookieParams();

    /**
     * Returns cookie parameters for the session
     * 
     * @param array $params
     * @return array
     */
    public function setCookieParams(array $params);

    /**
     * Encodes session data
     * 
     * @return string
     */
    public function encode();

    /**
     * Serializes the session array
     * 
     * @return string
     */
    public function decode();

    /**
     * Destroys the session
     * 
     * @return boolean
     */
    public function destroy();
}

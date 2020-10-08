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

use Krystal\Session\ManagerInterface;
use Krystal\Session\Adapter\SaveHandlerInterface;
use Krystal\Http\PersistentStorageInterface;
use Krystal\Http\CookieBagInterface;
use RuntimeException;
use Closure;

final class SessionBag implements SessionBagInterface, PersistentStorageInterface
{
    /**
     * Local session data
     * 
     * @var array
     */
    private $session = array();

    /**
     * Cookie bag which is basically used to remove session cookies
     * 
     * @var \Krystal\Http\CookieBagInterface
     */
    private $cookieBag;

    /**
     * Validation service for session uniqueness
     * 
     * @var \Krystal\Session\SessionValidatorInterface
     */
    private $sessionValidator;

    /**
     * State initialization
     * 
     * @param \Krystal\Http\CookieBagInterface $cookieBag
     * @param \Krystal\Session\SessionValidatorInterface $sessionValidator
     * @param \Krystal\Session\Adapter\SaveHandlerInterface $adapter Optional save handler
     * @return void
     */
    public function __construct(CookieBagInterface $cookieBag, SessionValidatorInterface $sessionValidator, SaveHandlerInterface $handler = null)
    {
        $this->cookieBag = $cookieBag;
        $this->sessionValidator = $sessionValidator;

        if (!is_null($handler)) {
            $this->initHandler($handler);
        }
    }

    /**
     * Writes the data and closes the session
     * 
     * @return void
     */
    public function __destruct()
    {
        if ($this->isStarted()) {
            session_write_close();
        }
    }

    /**
     * Initializes the session storage adapter
     * 
     * @param \Krystal\Session\Adapter\SaveHandlerInterface $handler
     * @return boolean
     */
    private function initHandler(SaveHandlerInterface $handler)
    {
        return session_set_save_handler(array($handler, 'open'), 
                                        array($handler, 'close'), 
                                        array($handler, 'read'), 
                                        array($handler, 'write'), 
                                        array($handler, 'destroy'), 
                                        array($handler, 'gc'));
    }

    /**
     * Removes all data from session
     * 
     * @return void
     */
    public function removeAll()
    {
        $this->session = array();
    }

    /**
     * Returns all session data
     * 
     * @return array
     */
    public function getAll()
    {
        return $this->session;
    }

    /**
     * Checks whether session is empty
     * 
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->session);
    }

    /**
     * Tells whether session is valid
     * 
     * @return boolean
     */
    public function isValid()
    {
        return $this->sessionValidator->isValid($this);
    }

    /**
     * Starts a new session or continues existing one
     * 
     * @param array $options Cookie options
     * @return boolean
     */
    public function start(array $options = array())
    {
        if ($this->isStarted() === false) {
            // Cookie parameters must be defined before session_start()!
            if (!empty($options)) {
                $this->setCookieParams($options);
            }

            if (!session_start()) {
                return false;
            }
        }

        // Reference is important! Because we are going to deal with $_SESSION itself
        // not with its copy, or this simply won't work the way we expect
        $this->session =& $_SESSION;

        // Store unique data to validator
        $this->sessionValidator->write($this);

        return true;
    }

    /**
     * Regenerates session's id
     * 
     * @return boolean
     */
    public function regenerate()
    {
        return session_regenerate_id(true);
    }

    /**
     * Defines session's name
     * 
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        return session_name($name);
    }

    /**
     * Returns session's name
     * 
     * @return string
     */
    public function getName()
    {
        return session_name();
    }

    /**
     * Checks whether session is started
     * 
     * @return boolean
     */
    public function isStarted()
    {
        return $this->getId() !== '';
    }

    /**
     * Returns current session's id
     * 
     * @return string
     */
    public function getId()
    {
        return session_id();
    }

    /**
     * Sets session id
     * 
     * @param string $id
     * @return void
     */
    public function setId($id)
    {
        return session_id($id);
    }

    /**
     * Stores data in session's storage
     * 
     * @param string $key
     * @param mixed $value
     * @return \Krystal\Session\SessionBag
     */
    public function set($key, $value)
    {
        $this->session[$key] = $value;
        return $this;
    }

    /**
     * Set many keys at once
     * 
     * @param array $collection
     * @return \Krystal\Session\SessionBag
     */
    public function setMany(array $collection)
    {
        foreach ($collection as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

    /**
     * Determines whether session has a key
     * 
     * @param string $key
     * @return boolean
     */
    public function has($key)
    {
        return array_key_exists($key, $this->session);
    }

    /**
     * Determines whether all keys present in the session storage
     * 
     * @param array $keys
     * @return boolean
     */
    public function hasMany(array $keys)
    {
        foreach ($keys as $key) {
            if (!$this->has($key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns data invoking callback only once
     * 
     * @param string $key
     * @param \Closure $callback Callback function that returns a value
     * @return mixed
     */
    public function getOnce($key, Closure $callback)
    {
        if ($this->has($key) !== false) {
            return $this->session[$key];
        } else {
            $value = $callback();
            $this->set($key, $value);
            return $value;
        }
    }

    /**
     * Reads data from a session
     * 
     * @param string $key
     * @param mixed $default Default value to be returned if request key doesn't exist
     * @return mixed
     */
    public function get($key, $default = false)
    {
        if ($this->has($key) !== false) {
            return $this->session[$key];
        } else {
            return $default;
        }
    }

    /**
     * Returns flashed session key (will be removed after retrieval)
     * 
     * @param string $key
     * @throws \RuntimeException If attempted to read non-existing key
     * @return string
     */
    public function getFlashed($key)
    {
        if ($this->has($key)) {
            // Save it before removing
            $value = $this->get($key);

            // And remove it
            $this->remove($key);
            return $value;
        } else {
            throw new RuntimeException(sprintf('Attempted to read non-existing session key "%s"', $key));
        }
    }

    /**
     * Removes a key from the storage
     * 
     * @param string $key
     * @return boolean
     */
    public function remove($key)
    {
        if ($this->has($key)) {
            unset($this->session[$key]);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Remove many session keys with their values at once
     * 
     * @param array $keys A collection of keys
     * @return boolean
     */
    public function removeMany(array $keys)
    {
        foreach ($keys as $key) {
            $this->remove($key);
        }

        return true;
    }

    /**
     * Free all session variables
     * 
     * @return void
     */
    public function free()
    {
        return session_unset();
    }

    /**
     * Returns true
     * if sessions are enabled, and one exists.  
     * 
     * @return boolean
     */
    public function isActive()
    {
        return $this->getId() !== '' && !empty($this->session);
    }

    /**
     * Checks if sessions are disabled
     * 
     * @return boolean
     */
    public function isDisabled()
    {
        return $this->getId() === '' && empty($this->session);
    }

    /**
     * Checks if sessions are enabled, but none exists 
     * 
     * @return boolean
     */
    public function isNone()
    {
        return $this->getId() !== '' && empty($this->session);
    }

    /**
     * Returns cookie storage parameters for the session
     * 
     * @return array
     */
    public function getCookieParams()
    {
        return session_get_cookie_params();
    }

    /**
     * Returns cookie parameters for the session
     * 
     * @param array $params
     * @return array
     */
    public function setCookieParams(array $params)
    {
        $defaults = $this->getCookieParams();

        // Override defaults if present
        $params = array_merge($defaults, $params);

        return session_set_cookie_params(
            intval($params['lifetime']), 
            $params['path'], 
            $params['domain'], 
            (bool) $params['secure'], 
            (bool) $params['httponly']
        );
    }

    /**
     * Encodes session data
     * 
     * @return string
     */
    public function encode()
    {
        return session_encode();
    }

    /**
     * Serializes the session array
     * 
     * @return string
     */
    public function decode()
    {
        return session_decode();
    }

    /**
     * Destroys the session
     * 
     * @return boolean
     */
    public function destroy()
    {
        // Erase the id on the client side
        if ($this->cookieBag->has($this->getName())) {
            $this->cookieBag->remove($this->getName());
        }

        // Erase on the server side
        return session_destroy();
    }
}

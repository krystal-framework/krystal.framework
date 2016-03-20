<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http;

use Krystal\Date\TimeHelper;
use RuntimeException;
use InvalidArgumentException;
use UnexpectedValueException;

final class CookieBag implements CookieBagInterface, PersistentStorageInterface
{
    /**
     * The local reference to $_COOKIE
     * 
     * @var array
     */
    private $cookies = array();

    /**
     * State initialization
     * 
     * @param array $cookie
     * @return void
     */
    public function __construct(array &$cookie)
    {
        $this->cookies  = &$cookie;
    }

    /**
     * Checks whether we have at least one key stored in
     * 
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->cookies);
    }

    /**
     * Removes all cookies from client machine
     * 
     * @return boolean True if all cookies have been erased, False if nothing to erase
     */
    public function removeAll()
    {
        if ($this->hasCookies() !== false) {
            foreach ($this->cookies as $key => $val) {
                $this->delete($key);
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns cookie array
     * 
     * @return array
     */
    public function getAll()
    {
        return $this->cookies;
    }

    /**
     * Sets a cookie
     * 
     * @param string $key Cookie key
     * @param string $value Cookie value
     * @param integer $ttl Cookie time to live in seconds
     * @param string $path The path on the server in which the cookie will be available on. Defaults to /
     * @param boolean $secure Indicates that the cookie should only be transmitted over a secure HTTPS connection from the client
     * @param boolean $httpOnly When TRUE the cookie will be made accessible only through the HTTP protocol
     * @param boolean $raw Whether to send a cookie without urlencoding the cookie value
     * @throws \InvalidArgumentException if either $key or $value isn't scalar by type
     * @throws \UnexpectedValueException If trying to set a key that contains a dot
     * @return boolean
     */
    public function set($key, $value, $ttl = TimeHelper::YEAR, $path = '/', $secure = false, $httpOnly = false, $raw = false)
    {
        if (strpos($key, '.') !== false) {
            throw new UnexpectedValueException('Setting cookie keys that contain a dot is not allowed');
        }

        if (!is_scalar($key) || !is_scalar($value)) {
            throw new InvalidArgumentException('Cookie writing must done only with scalar values');
        }

        // Arguments to pass either to setrawcookie() or setcookie()
        // By doing it this way, we adhere to the DRY principle
        $arguments = array($key, (string) $value, $ttl + abs(time()), $path, $_SERVER['HTTP_HOST'], $secure, $httpOnly);

        // Quick workaround that makes a cookie available on the current HTTP request
        $this->cookies[$key] = $value;

        // And finally prepare a function to be called
        if ($raw === true) {
            $function = 'setrawcookie';
        } else {
            $function = 'setcookie';
        }

        // If setcookie() successfully runs, it will return TRUE. However, this does not indicate whether the user accepted the cookie
        // but anyway it's better than void
        return call_user_func_array($function, $arguments);
    }

    /**
     * Retrieves a key form cookies
     * 
     * @param string $key
     * @throws \RuntimeException if $key does not exist in cookies`
     * @return string
     */
    public function get($key)
    {
        if ($this->has($key)) {
            return $this->cookies[$key];
        } else {
            throw new RuntimeException(sprintf(
                'Attempted to read non-existing cookie "%s"', $key
            ));
        }
    }

    /**
     * Removes a cookie by its associated key
     * 
     * @param string $key
     * @return boolean True if deleted, false if not
     */
    public function remove($key)
    {
        if ($this->has($key)) {
            // Set cookie in the past
            $this->set($key, '', -86400);
            // And also make sure it won't be available on the current HTTP request
            unset($this->cookies[$key]);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determines whether cookie key exists
     * 
     * @param string $key
     * @return boolean TRUE if exists, FALSE if not
     */
    public function has($key)
    {
        return isset($this->cookies[$key]);
    }
}

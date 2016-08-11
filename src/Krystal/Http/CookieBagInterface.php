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

interface CookieBagInterface
{
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
     * @return boolean
     */
    public function set($key, $value, $ttl = TimeHelper::YEAR, $path = '/', $secure = false, $httpOnly = false, $raw = false);

    /**
     * Sets a encrypted cookie
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
    public function setEncrypted($key, $value, $ttl = TimeHelper::YEAR, $path = '/', $secure = false, $httpOnly = false, $raw = false);

    /**
     * Retrieves a key form cookies decoding its value
     * 
     * @param string $key
     * @throws \RuntimeException if $key does not exist in cookies`
     * @return string
     */
    public function getEncrypted($key);
}

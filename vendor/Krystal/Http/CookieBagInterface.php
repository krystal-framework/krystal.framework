<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http;

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
	public function set($key, $value, $ttl = 0, $path = '/', $secure = false, $httpOnly = false, $raw = false);
}

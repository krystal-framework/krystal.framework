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

interface HeaderBagInterface
{
	/**
	 * Returns all request headers
	 * 
	 * @return array
	 */
	public function getAllRequestHeaders();

	/**
	 * Determines whether request header has been sent
	 * 
	 * @param string $header
	 * @return boolean
	 */
	public function hasRequestHeader($header);

	/**
	 * Returns request header if present
	 * 
	 * @param string $header
	 * @param boolean $default Default value to be returned in case requested one doesn't exist
	 * @return string
	 */
	public function getRequestHeader($header, $default = false);

	/**
	 * Sets status code
	 * 
	 * @param integer $code
	 * @return \Krystal\Http\HeaderBag
	 */
	public function setStatusCode($code);

	/**
	 * Set many headers at once and clear all previous ones
	 * 
	 * @param array $headers
	 * @return \Krystal\Http\HeaderBag
	 */
	public function setMany(array $headers);

	/**
	 * Clears all previous headers and adds a new one
	 * 
	 * @param string $header
	 * @return \Krystal\Http\HeaderBag
	 */
	public function set($header);

	/**
	 * Appends several headers at once
	 * 
	 * @param array $headers
	 * @return \Krystal\Http\HeaderBag
	 */
	public function appendMany(array $headers);

	/**
	 * Appends a pair
	 * 
	 * @param string $key
	 * @param string $value
	 * @return \Krystal\Http\HeaderBag
	 */
	public function appendPair($key, $value);

	/**
	 * Append several pairs
	 * 
	 * @param array $headers
	 * @return \Krystal\Http\HeaderBag
	 */
	public function appendPairs(array $headers);

	/**
	 * Appends a header
	 * 
	 * @param string $header
	 * @return \Krystal\Http\HeaderBag
	 */
	public function append($header);

	/**
	 * Checks whether header has been appended before
	 * 
	 * @param string $header
	 * @return boolean
	 */
	public function has($header);

	/**
	 * Clears the stack
	 * 
	 * @return \Krystal\Http\HeaderBag
	 */
	public function clear();

	/**
	 * Send headers
	 * 
	 * @param boolean $replace Whether to override a header on collision
	 * @return \Krystal\Http\HeaderBag
	 */
	public function send($replace = true);
}

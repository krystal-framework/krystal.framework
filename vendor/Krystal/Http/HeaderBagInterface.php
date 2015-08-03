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
	 * Clears all previous headers and adds a new one
	 * 
	 * @param string $header
	 * @return \Krystal\Http\HeaderBag
	 */
	public function set($header);

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
	 * @return void
	 */
	public function send();
}

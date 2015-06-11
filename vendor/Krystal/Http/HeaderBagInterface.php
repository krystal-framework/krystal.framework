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
	 * Appends a header
	 * 
	 * @param string $header
	 * @return void
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
	 * @return array
	 */
	public function clear();

	/**
	 * Send headers
	 * 
	 * @return void
	 */
	public function send();
}

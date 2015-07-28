<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form;

interface FlashBagInterface
{
	/**
	 * Checks whether message key exists
	 * 
	 * @param string $key
	 * @return boolean
	 */
	public function has($key);

	/**
	 * Sets a message by given key name
	 * 
	 * @param string $key
	 * @param string $message
	 * @return FlashMessenger
	 */
	public function set($key, $message);

	/**
	 * Returns a message associated with a given key
	 * 
	 * @param string $key
	 * @throws RuntimeException If attempted to read non-existing key
	 * @return string
	 */
	public function get($key);
}

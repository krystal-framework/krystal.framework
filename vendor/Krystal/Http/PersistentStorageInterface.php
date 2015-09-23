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

/* Cookie and Session Bags must implement this contract */
interface PersistentStorageInterface
{
	/**
	 * Checks whether we have at least one key stored in
	 * 
	 * @return boolean
	 */
	public function isEmpty();

	/**
	 * Removes all data we have
	 * 
	 * @return boolean
	 */
	public function removeAll();

	/**
	 * Returns all data array
	 * 
	 * @return array
	 */
	public function getAll();

	/**
	 * Retrieves a key form storage
	 * 
	 * @param string $key
	 * @throws \RuntimeException if $key does not exist
	 * @return string
	 */
	public function get($key);

	/**
	 * Removes a key from a storage
	 * 
	 * @param string $key
	 * @return boolean True if deleted, false if not
	 */
	public function remove($key);

	/**
	 * Determines whether cookie key exists
	 * 
	 * @param string $key
	 * @return boolean TRUE if exists, FALSE if not
	 */
	public function has($key);
}

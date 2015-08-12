<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Session\Adapter;

/**
 * Each session storage handler must implement this interface
 */
interface SaveHandlerInterface
{
	/**
	 * Opens a new session internally
	 * 
	 * @param string $path Session path
	 * @param string $name
	 * @return boolean
	 */
	public function open($path, $name);

	/**
	 * Closes the session internally
	 * 
	 * @return boolean
	 */
	public function close();

	/**
	 * Writes data to the session
	 * 
	 * @param string $id Session id
	 * @param array $data Session data
	 * @return boolean
	 */
	public function write($id, $data);

	/**
	 * Reads data from the session
	 * 
	 * @param string $id Session id (used internally by PHP engine)
	 * @return string (Always! or PHP crashes)
	 */
	public function read($id);

	/**
	 * Deletes data from the session
	 * 
	 * @param string $id Session id
	 * @throws \PDOException If an error occurred
	 * @return boolean true always
	 */
	public function destroy($id);

	/**
	 * Garbage collection
	 * 
	 * @param integer $maxlifetime
	 * @return boolean Depending on success
	 */
	public function gc($maxlifetime);
}

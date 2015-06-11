<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Session;

/**
 * Each session storage's adapter must implement this interface
 */
interface SaveHandlerInterface
{
	/**
	 * Opens a session
	 * 
	 * @param string $save_path
	 * @param string $name
	 * @return void
	 */
	public function open($save_path, $name);

	/**
	 * Closes the session
	 * 
	 * @return boolean
	 */
	public function close();

	/**
	 * Write session data
	 * 
	 * @param string $session_id
	 * @param string $session_data
	 * @return boolean
	 */
	public function write($session_id, $session_data);

	/**
	 * Read session data
	 * 
	 * @param string $session_id
	 * @return mixed
	 */
	public function read($session_id);

	/**
	 * Destroys the session
	 * 
	 * @param string $session_id
	 * @return void
	 */
	public function destroy($session_id);

	/**
	 * Garbage collection
	 * 
	 * @param integer $maxlifetime Maximal lifetime
	 * @return void
	 */
	public function gc($maxlifetime);
}

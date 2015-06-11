<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Authentication;

interface AuthManagerInterface
{
	/**
	 * Returns stored data
	 * 
	 * @param array $key Optionally returned data can be filtered by a key
	 * @return array|boolean False if nothing stored
	 */
	//public function getData($key = null);

	/**
	 * Checks whether user is logged in
	 * 
	 * @return boolean
	 */
	public function isLoggedIn();

	/**
	 * Erases all credentials
	 * 
	 * @return void
	 */
	public function logout();
}

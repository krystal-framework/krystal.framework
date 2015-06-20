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

interface UserAuthServiceInterface
{
	//@TODO Get role and get id
	
	/**
	 * Attempts to authenticate a user
	 * 
	 * @param string $login
	 * @param string $password
	 * @param boolean $remember Whether to remember
	 * @param boolean $hash Whether to hash password
	 * @return boolean
	 */
	public function authenticate($login, $password, $remember, $hash = true);

	/**
	 * Log-outs a user
	 * 
	 * @return void
	 */
	public function logout();

	/**
	 * Checks whether a user is logged in
	 * 
	 * @return boolean
	 */
	public function isLoggedIn();

	/**
	 * Disables authorization checking
	 * 
	 * @return void
	 */
	public function disableAuthCheck();
}
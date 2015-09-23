<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Authentication;

interface UserAuthServiceInterface
{
	/**
	 * Returns stored user id
	 * 
	 * @return string
	 */
	public function getId();

	/**
	 * Returns stored user role
	 * 
	 * @return string
	 */
	public function getRole();

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
}

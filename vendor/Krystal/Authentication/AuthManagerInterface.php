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

interface AuthManagerInterface
{
	/**
	 * Stores user's id
	 * 
	 * @param string $id
	 * @return \Krystal\Authentication\AuthManager
	 */
	public function storeId($id);

	/**
	 * Returns user's id
	 * 
	 * @return string
	 */
	public function getId();

	/**
	 * Stores a role
	 * 
	 * @param string $role
	 * @return \Krystal\Authentication\AuthManager
	 */
	public function storeRole($role);

	/**
	 * Returns stored role
	 * 
	 * @return string
	 */
	public function getRole();

	/**
	 * Checks whether user is logged in, only once
	 * 
	 * @return boolean
	 */
	public function isLoggedIn();

	/**
	 * Checks whether at least one role belongs to current session
	 * 
	 * @param array $roles
	 * @return boolean
	 */
	public function isAllowed(array $roles);

	/**
	 * Logins a user
	 * 
	 * @param string $login
	 * @param string $passwordHash
	 * @param boolean Whether to enable "remember me" functionality
	 * @return void
	 */
	public function login($login, $passwordHash, $remember = false);

	/**
	 * Erases all credentials
	 * 
	 * @return void
	 */
	public function logout();
}

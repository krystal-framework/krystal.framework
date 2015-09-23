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

interface UserBagInterface
{
	/**
	 * Returns a login
	 * 
	 * @return string
	 */
	public function getLogin();

	/**
	 * Sets a login
	 * 
	 * @param string $login
	 * @return \Krystal\Authentication\Cookie\UserBag
	 */
	public function setLogin($login);

	/**
	 * Returns password hash
	 * 
	 * @return string
	 */
	public function getPasswordHash();

	/**
	 * Defines password hash
	 * 
	 * @param string $passwordHash
	 * @return \Krystal\Authentication\Cookie\UserBag
	 */
	public function setPasswordHash($passwordHash);
}

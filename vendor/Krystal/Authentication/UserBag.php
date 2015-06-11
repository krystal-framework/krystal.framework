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

final class UserBag implements UserBagInterface 
{
	/**
	 * User's login
	 * 
	 * @var string
	 */
	private $login;

	/**
	 * User's password hash
	 * 
	 * @var string
	 */
	private $passwordHash;
	
	private $id;

	/**
	 * Returns a login
	 * 
	 * @return string
	 */
	public function getLogin()
	{
		return $this->login;
	}

	/**
	 * Sets a login
	 * 
	 * @param string $login
	 * @return \Krystal\Authentication\Cookie\UserBag
	 */
	public function setLogin($login)
	{
		$this->login = $login;
		return $this;
	}

	/**
	 * Returns password hash
	 * 
	 * @return string
	 */
	public function getPasswordHash()
	{
		return $this->passwordHash;
	}

	/**
	 * Defines password hash
	 * 
	 * @param string $passwordHash
	 * @return \Krystal\Authentication\Cookie\UserBag
	 */
	public function setPasswordHash($passwordHash)
	{
		$this->passwordHash = $passwordHash;
		return $this;
	}	
}

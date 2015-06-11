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

use Krystal\Session\SessionBagInterface;
use Krystal\Authentication\Cookie\ReAuthInterface;
use InvalidArgumentException;

final class AuthManager implements AuthManagerInterface
{
	/**
	 * Credentials storage
	 * 
	 * @var \Krystal\Session\SessionBagInterface
	 */
	private $sessionBag;

	/**
	 * Used to re-authenticate from storage
	 * 
	 * @var \Krystal\Authentication\Cookie\ReAuthInterface
	 */
	private $reAuth;

	/**
	 * Hash provider
	 * 
	 * @var \Krystal\Authentication\HashProvider
	 */
	private $hashProvider;

	/**
	 * Authentication service
	 * 
	 * @var \Krystal\Authentication\UserAuthServiceInterface
	 */
	private $authService;

	/**
	 * Namespace in session storage for authentication
	 * 
	 * @const string
	 */
	const AUTH_NAMESPACE = 'Krystal_AUTH';

	/**
	 * Tells whether authorization is enabled
	 * By default it's always active
	 * 
	 * @var boolean
	 */
	private $active = true;

	/**
	 * State initialization
	 * 
	 * @param \Krystal\Session\SessionBagInterface $sessionBag
	 * @param \Krystal\Authentication\Cookie\ReAuthInterface $reAuth
	 * @param \Krystal\Authentication\HashProviderInterface
	 * @return void
	 */
	public function __construct(SessionBagInterface $sessionBag, ReAuthInterface $reAuth, HashProviderInterface $hashProvider)
	{
		$this->sessionBag = $sessionBag;
		$this->reAuth = $reAuth;
		$this->hashProvider = $hashProvider;
	}

	/**
	 * Stores user's id
	 * 
	 * @param string $id
	 * @return \Krystal\Authentication\AuthManager
	 */
	public function storeId($id)
	{
		$this->storeData('user_id', $id);
		return $this;
	}

	/**
	 * Returns user's id
	 * 
	 * @return string
	 */
	public function getId()
	{
		return $this->getData('user_id');
	}

	/**
	 * Stores a role
	 * 
	 * @param string $role
	 * @return \Krystal\Authentication\AuthManager
	 */
	public function storeRole($role)
	{
		$this->storeData('user_role', $role);
		return $this;
	}

	/**
	 * Returns stored role
	 * 
	 * @return string
	 */
	public function getRole()
	{
		return $this->getData('user_role');
	}
	
	/**
	 * Sets whether AuthManager is active or not
	 * 
	 * @param boolean $active The state
	 * @return void
	 */
	public function setActive($active)
	{
		$this->active = (bool) $active;
	}

	/**
	 * Tells whether authentication is active
	 * 
	 * @return boolean
	 */
	public function isActive()
	{
		return $this->active;
	}

	/**
	 * Defines match visitor
	 * 
	 * @param \Krystal\Authentication\UserAuthServiceInterface $authService
	 * @return void
	 */
	public function setAuthService(UserAuthServiceInterface $authService)
	{
		$this->authService = $authService;
	}

	public function storeData($key, $value)
	{
		$this->sessionBag->set($key, $value);
		return $this;
	}
	
	public function getData($key, $default = false)
	{
		if ($this->sessionBag->has($key)){
			return $this->sessionBag->get($key);
		} else {
			return $default;
		}
	}

	/**
	 * Checks whether a data is stored in the storage
	 * 
	 * @return boolean
	 */
	private function has()
	{
		return $this->sessionBag->has(self::AUTH_NAMESPACE);
	}

	/**
	 * Logins a user
	 * 
	 * @param string $login
	 * @param string $passwordHash
	 * @param boolean Whether to enable "remember me" functionality
	 * @return void
	 */
	public function login($login, $passwordHash, $remember = false)
	{
		if ((bool) $remember == true) {
			// Write to client's cookies
			$this->reAuth->store($login, $passwordHash);
		}

		// Store it
		$this->sessionBag->set(self::AUTH_NAMESPACE, array(
			'login' => $login,
			'passwordHash' => $passwordHash
		));
	}

	/**
	 * Checks whether user is logged in
	 * 
	 * @return boolean
	 */
	public function isLoggedIn()
	{
		if (!$this->has()) {

			// Now try to find only in cookies, if found prepare a bag
			if ($this->reAuth->isStored() && (!$this->has())) {
				$userBag = $this->reAuth->getUserBag();
			}

			// If session namespace is filled up and at the same time data stored in cookies
			if (($this->has() && $this->reAuth->isStored()) || $this->has()) {
				$data = $this->sessionBag->get(self::AUTH_NAMESPACE);
				
				$userBag = new UserBag();
				$userBag->setLogin($data['login'])
						->setPasswordHash($data['passwordHash']);
			}
			
			// If $userBag wasn't created so far, that means user isn't logged at all
			if (!isset($userBag)) {
				return false;
			}

			// Now let's invoke our defined match visitor
			$authResult = $this->authService->authenticate($userBag->getLogin(), $userBag->getPasswordHash(), false, false);

			if ($authResult == true) {
				// Remember success, in order not to query on each request
				$this->login($userBag->getLogin(), $userBag->getPasswordHash());
				return true;
			}

			return false;
			
		} else {
			
			return true;
		}
	}

	/**
	 * Erases all credentials
	 * 
	 * @return void
	 */
	public function logout()
	{
		if ($this->has()) {
			$this->sessionBag->remove(self::AUTH_NAMESPACE);
		}

		if ($this->reAuth->isStored()) {
			$this->reAuth->clear();
		}
	}
}

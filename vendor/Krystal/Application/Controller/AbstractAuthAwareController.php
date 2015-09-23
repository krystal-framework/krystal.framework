<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Controller;

abstract class AbstractAuthAwareController extends AbstractController
{
	/**
	 * Whether authorization manager is active
	 * 
	 * @var boolean
	 */
	protected $authActive = true;

	/**
	 * @const string
	 */
	const PARAM_RBAC_KEY = 'disallow';

	/**
	 * Returns user manager's service
	 * 
	 * @return \Krystal\Authentication\UserAuthServiceInterface
	 */
	abstract protected function getAuthService();

	/**
	 * Invoked when a user is logged in
	 * 
	 * @return void
	 */
	abstract protected function onSuccess();

	/**
	 * Invoked when a not-logged user attempted to gain access
	 * 
	 * @return void
	 */
	abstract protected function onFailure();

	/**
	 * Invoked if a user has no enough rights to access controller's action
	 * 
	 * @return void
	 */
	abstract protected function onNoRights();

	/**
	 * Handles authorization process
	 * You should never edit this method
	 * 
	 * @return void
	 */
	final protected function onAuth()
	{
		$authService = $this->getAuthService();

		$this->authManager->setAuthService($authService);

		if ($this->authActive === true) {
			if ($authService->isLoggedIn()) {
				$this->onSuccess();
			} else {
				$this->onFailure();
			}
		}

		// Now handle RBRAC if present
		if ($this->hasRbacRules()) {
			// Check if provided user has enough privileges
			if (!$this->hasRight($authService->getRole())) {
				$this->onNoRights();
			}
		}
	}

	/**
	 * Tells whether descendant controller has RBAC rules
	 * 
	 * @return boolean
	 */
	final protected function hasRbacRules()
	{
		return $this->hasOption(self::PARAM_RBAC_KEY) && is_array($this->getOptions(self::PARAM_RBAC_KEY));
	}

	/**
	 * Checks whether provided role has a right to perform an action
	 * 
	 * @param string $role Role to be checked
	 * @return boolean
	 */
	final protected function hasRight($role)
	{
		return !in_array($role, $this->getOptions(self::PARAM_RBAC_KEY));
	}
}

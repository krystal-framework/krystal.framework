<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Security;

interface CsrfProtectorInterface
{
	/**
	 * Prepares to run
	 * 
	 * @return void
	 */
	public function prepare();

	/**
	 * Returns generated token
	 * 
	 * @return string
	 */
	public function getToken();

	/**
	 * Checks whether token is expired
	 * 
	 * @return boolean
	 */
	public function isExpired();

	/**
	 * Check whether coming token is valid
	 * 
	 * @param string $token Target token to be validated
	 * @return boolean
	 */
	public function isValid($token);
}

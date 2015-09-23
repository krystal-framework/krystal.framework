<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Security;

use Krystal\Session\SessionBagInterface;
use RuntimeException;

final class CsrfProtector implements CsrfProtectorInterface
{
	/**
	 * Session bag
	 * 
	 * @var \Krystal\Session\SessionBagInterface
	 */
	private $sessionBag;

	/**
	 * Time to live in seconds for the token
	 * 
	 * @var integer
	 */
	private $ttl;

	/**
	 * Checks whether it's ready to run
	 * 
	 * @var boolean
	 */
	private $prepared = false;

	const CSRF_TKN_NAME = 'csrf_token';
	const CSRF_TKN_TIME = 'csrf_time';

	/**
	 * State initialization
	 * 
	 * @param \Krystal\Session\SessionBagInterface $sessionBag
	 * @param integer $ttl Time to live in seconds
	 * @return void
	 */
	public function __construct(SessionBagInterface $sessionBag, $ttl = 300)
	{
		$this->sessionBag = $sessionBag;
		$this->ttl = $ttl;
	}

	/**
	 * Prepares to run
	 * 
	 * @return void
	 */
	public function prepare()
	{
		if (!$this->sessionBag->has(self::CSRF_TKN_NAME)) {
			$this->sessionBag->set(self::CSRF_TKN_NAME, $this->generateToken());
			$this->sessionBag->set(self::CSRF_TKN_TIME, time());
		}

		$this->prepared = true;
	}

	/**
	 * Returns generated token
	 * 
	 * @return string
	 */
	public function getToken()
	{
		$this->validatePrepared();
		return $this->sessionBag->get(self::CSRF_TKN_NAME);
	}

	/**
	 * Checks whether token is expired
	 * 
	 * @return boolean
	 */
	public function isExpired()
	{
		$this->validatePrepared();

		if (!$this->sessionBag->has(self::CSRF_TKN_TIME)) {
			return true;
		} else {
			$age = time() - $this->sessionBag->get(self::CSRF_TKN_TIME);
			return $age >= $this->ttl;
		}
	}

	/**
	 * Check whether coming token is valid
	 * 
	 * @param string $token Target token to be validated
	 * @return boolean
	 */
	public function isValid($token)
	{
		$this->validatePrepared();

		return $this->sessionBag->has(self::CSRF_TKN_NAME) && 
			   $this->sessionBag->has(self::CSRF_TKN_TIME) && 
			   $this->sessionBag->get(self::CSRF_TKN_NAME) === $token;
	}

	/**
	 * Checks whether it's ready
	 * 
	 * @throws \RuntimeException If not ready
	 * @return void
	 */
	private function validatePrepared()
	{
		if ($this->prepared !== true) {
			throw new RuntimeException('Before using any of CsrfProtector methods, prepare() must be called first');
		}
	}

	/**
	 * Returns secure token
	 * 
	 * @return string
	 */
	private function generateToken()
	{
		return md5(uniqid(rand(), true));
	}
}

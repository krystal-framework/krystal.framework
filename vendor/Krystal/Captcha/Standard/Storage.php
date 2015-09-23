<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Captcha\Standard;

final class Storage implements StorageInterface
{
	/**
	 * The key in storage engine
	 * 
	 * @const string
	 */
	const CAPTCHA_ID = 'captcha';

	/**
	 * Session manager
	 * 
	 * @var \Krystal\Session\SessionBagInterface
	 */
	private $sessionBag;

	/**
	 * State initialization
	 * 
	 * @param \Krystal\Session\SessionBagInterface $sessionBag
	 * @return void
	 */
	public function __construct($sessionBag)
	{
		$this->sessionBag = $sessionBag;
	}

	/**
	 * Clears the CAPTCHA value
	 * This is especially useful when we validation is passed
	 * 
	 * @return void
	 */
	public function clear()
	{
		if ($this->has()) {
			return $this->sessionBag->remove(self::CAPTCHA_ID);
		}
	}

	/**
	 * Updates the answer
	 * 
	 * @param string $answer
	 * @return boolean
	 */
	public function set($answer)
	{
		return $this->sessionBag->set(self::CAPTCHA_ID, $answer);
	}

	/**
	 * Checks whether CAPTCHA identification is stored
	 * 
	 * @return boolean
	 */
	public function has()
	{
		return $this->sessionBag->has(self::CAPTCHA_ID);
	}

	/**
	 * Returns CAPTCHA answer
	 * 
	 * @return string
	 */
	public function get()
	{
		return $this->sessionBag->get(self::CAPTCHA_ID);
	}
}

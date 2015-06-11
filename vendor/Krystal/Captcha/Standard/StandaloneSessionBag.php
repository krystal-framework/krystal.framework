<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Captcha\Standard;

/**
 * Should only be used when the CAPTCHA component is used as standalone library
 * Yeah this class is not testable because of global state and session_start() inside constructor
 */
final class StandaloneSessionBag
{
	/**
	 * Local session data
	 * 
	 * @var array
	 */
	private $session = array();

	/**
	 * State initialization
	 * 
	 * @return void
	 */
	public function __construct()
	{
		@session_start();
		$this->session = &$_SESSION;
	}

	/**
	 * Checks whether key exists
	 * 
	 * @param string $key
	 * @return boolean
	 */
	public function has($key)
	{
		return isset($this->session[$key]);
	}

	/**
	 * Deletes a key
	 * 
	 * @param string $key
	 * @return void
	 */
	public function remove($key)
	{
		unset($this->session[$key]);
	}

	/**
	 * Writes data
	 * 
	 * @param array $data
	 * @return void
	 */
	public function set($key, $value)
	{
		$this->session[$key] = $value;
	}

	/**
	 * Returns a value from session storage
	 * 
	 * @param string $key
	 * @return mixed
	 */
	public function get($key)
	{
		return $this->session[$key];
	}
}

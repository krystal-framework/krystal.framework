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

final class HashProvider implements HashProviderInterface
{
	/**
	 * Secure salt
	 * 
	 * @var string
	 */
	private $salt;

	/**
	 * Amount of iterations
	 * 
	 * @var integer
	 */
	private $iterations;

	/**
	 * State initialization
	 * 
	 * @param string $salt
	 * @return string
	 */
	public function __construct($salt = null, $iterations = 1)
	{
		$this->salt = $salt;
		$this->iterations = $iterations;
	}

	/**
	 * Hashes a string
	 * 
	 * @param string $key
	 * @return string
	 */
	public function hash($string)
	{
		return sha1($string);
	}
}

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

interface StorageInterface
{
	/**
	 * Updates the answer
	 * 
	 * @param string $answer
	 * @return boolean
	 */
	public function set($answer);

	/**
	 * Checks whether CAPTCHA identification is stored
	 * 
	 * @return boolean
	 */
	public function has();

	/**
	 * Returns CAPTCHA answer
	 * 
	 * @return string
	 */
	public function get();

	/**
	 * Clears the CAPTCHA value
	 * This is especially useful when we validation is passed
	 * 
	 * @return void
	 */
	public function clear();
}

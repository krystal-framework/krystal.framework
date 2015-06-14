<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form;

interface InputInterface
{
	/**
	 * Checks whether input has a key
	 * 
	 * @param string $key
	 * @return boolean
	 */
	public function has($key);

	/**
	 * Returns input's value by its associated key
	 * 
	 * @param string $key
	 * @param mixed $default Default value to be returned in case requested one doesn't exist
	 * @return string
	 */
	public function get($key, $default = false);

	/**
	 * Overdress the input array
	 * 
	 * @param array $input
	 * @return void
	 */
	public function setInput(array $input);

	/**
	 * Guesses input's name
	 * 
	 * @param string $key
	 * @return string
	 */
	public function guessName($key);

	/**
	 * Returns input's name with provided value
	 * 
	 * @param string $value
	 * @return string
	 */
	public function getWithValue($value);
}

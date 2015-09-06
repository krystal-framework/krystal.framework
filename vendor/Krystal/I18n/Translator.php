<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\I18n;

use InvalidArgumentException;

final class Translator implements TranslatorInterface
{
	/**
	 * Original messages with their associated translations
	 * 
	 * @var array
	 */
	private $dictionary = array();

	/**
	 * State initialization
	 * 
	 * @param array $dictionary
	 * @return void
	 */
	public function __construct(array $dictionary = array())
	{
		if (!empty($dictionary)) {
			$this->extend($dictionary);
		}
	}

	/**
	 * Extends first language array ($data)
	 * 
	 * @param array [$args]
	 * @return void
	 */
	public function extend()
	{
		foreach (func_get_args() as $array) {
			$this->dictionary = array_merge($this->dictionary, $array);
		}
	}

	/**
	 * Check whether a string exists in a stack
	 * 
	 * @param string $string The target string
	 * @return boolean
	 */
	public function exists($string)
	{
		return in_array($string, array_keys($this->dictionary));
	}

	/**
	 * Translate array values if possible
	 * 
	 * @param array $array
	 * @throws \InvalidArgumentException if at least one array's value isn't a string
	 * @return array
	 */
	public function translateArray(array $array)
	{
		$result = array();

		foreach ($array as $key => $value) {
			if (is_string($value)) {
				$result[$key] = $this->translate($value);
			} else {
				throw new InvalidArgumentException('Invalid array supplied');
			}
		}

		return $result;
	}

	/**
	 * Translates a string
	 * 
	 * @param string $default Default message
	 * @param string|array $placeholders Number of placeholder according to specified string
	 * @throws \InvalidArgumentException if first argument $message wasn't string
	 * @return string Translated string
	 */
	public function translate()
	{
		// Receive arguments
		$arguments = func_get_args();
		$message = array_shift($arguments);

		// Ensure the proper message received
		if (!is_string($message)) {
			throw new InvalidArgumentException(sprintf('Argument #1 $default must be a string, received "%s"', gettype($default)));
		}

		// The variables we are going to deal with
		$variables = array();

		// Iterate over arguments we have (message is stripped away)
		foreach ($arguments as $argument) {
			if (is_array($argument)) {
				// Array supplied as second argument, so here we don't need anything to do
				$variables = $argument;
				break;
			}

			// Only strings and integers are supported to be passed as arguments
			if (is_string($argument) || is_numeric($argument)) {
				array_push($variables, $argument);
			}
		}

		if (isset($this->dictionary[$message])) {
			return vsprintf($this->dictionary[$message], $variables);
		}

		return vsprintf($message, $variables);
	}
}

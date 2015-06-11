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

interface TranslatorInterface
{
	/**
	 * Translate array values if possible
	 * 
	 * @param array $array
	 * @return array
	 */
	public function translateArray(array $array);

	/**
	 * Formatted translator
	 * 
	 * @param string $target
	 * @return string
	 */
	public function ftranslate($target);

	/**
	 * Translates a string
	 * 
	 * @return string
	 */
	public function translate();

	/**
	 * Returns an array
	 * 
	 * @return array
	 */
	public function getData();

	/**
	 * Extends first language array ($data)
	 * 
	 * @param array [$args]
	 * @return void
	 */
	public function extend();

	/**
	 * Check whether a string exists in a stack
	 * 
	 * @param string $string The target string
	 * @return boolean TRUE if exists, FALSE if not
	 */
	public function exists($string);

	/**
	 * Checks whether string has at least one placeholder
	 * 
	 * @param string $message The string being checked
	 * @return integer|boolean A number of placeholder on success, FALSE if no placeholders
	 */
	public function hasPlaceholders($message);
}

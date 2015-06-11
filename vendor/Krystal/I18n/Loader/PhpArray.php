<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\I18n\Loader;

use InvalidArgumentException;

final class PhpArray extends LoaderAbstract
{
	/**
	 * Load from files
	 * 
	 * @param array $files
	 * @return boolean
	 */
	public function loadFromFiles(array $files)
	{
		foreach ($files as $file) {
			$this->loadFromFile($file);
		}

		return true;
	}

	/**
	 * @param array $array
	 * @return true
	 */
	public function loadFromArray($array)
	{
		$this->pushToStack($array);
		return true;
	}
	
	/**
	 * Loads/Appends an array from file
	 * 
	 * @param string $file
	 * @return boolean Depending on success
	 */
	public function loadFromFile($file)
	{
		if (is_file($file)) {
			$array = require($file);

			if ($this->isValid($array)) {
				$this->pushToStack($array);
				return true;

			} else {
				trigger_error(sprintf(
					'File returned incorrect value, it must be an array and only, not "%s"', gettype($array)
				));

				return false;
			}

		} else {
			throw new InvalidArgumentException(sprintf('It was not a file "%s"', $file));
		}
	}

	/**
	 * Loads from string
	 * 
	 * @throws BadMethodCallException
	 */
	public function loadFromString()
	{
		throw new BadMethodCallException(sprintf('Cannot use "%s"', __METHOD__));
	}
}

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
	private $data = array();

	/**
	 * Data loader
	 * 
	 * @var object
	 */
	private $loader;

	/**
	 * Default placeholder for strings
	 * 
	 * @const string
	 */
	const PLACEHOLDER = '%s';

	/**
	 * Default char-set
	 * 
	 * @const string
	 */
	const CHARSET = 'UTF-8';

	/**
	 * Processed values
	 * 
	 * @var array
	 */
	private $processed = array();

	/**
	 * @var array
	 */
	private $source = array();

	/**
	 * Constructor
	 * 
	 * @param object $loader
	 * @return void
	 */
	public function __construct($loader = null)
	{
		$this->loader = $loader;
	}

	/**
	 * Read everything into a memory
	 * 
	 * @return void
	 */
	public function initialize()
	{
		if ($this->loader !== null) {
			$this->source = $this->loader->getData();
			$this->data = $this->loader->getData();
		}
	}

	/**
	 * Return processed strings
	 * 
	 * @return array
	 */
	public function getProcessed()
	{
		return $this->processed;
	}

	/**
	 * Append one more processed string
	 * 
	 * @param string $string
	 * @return void
	 */
	private function appendProcessed($string)
	{
		array_push($this->processed, $string);
	}

	/**
	 * Returns an array
	 * 
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
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
			$this->data = array_merge($this->data, $array);
			$this->source = array_merge($this->data, $array);
		}
	}

	/**
	 * Check whether a string exists in a stack
	 * 
	 * @param string $string The target string
	 * @return boolean TRUE if exists, FALSE if not
	 */
	public function exists($string)
	{
		return in_array($string, array_keys($this->data));
	}

	/**
	 * Checks whether string has at least one placeholder
	 * 
	 * @param string $message The string being checked
	 * @return integer|boolean A number of placeholder on success, FALSE if no placeholders
	 */
	public function hasPlaceholders($message)
	{
		$count = mb_substr_count($message, self::PLACEHOLDER, self::CHARSET);
		return $count === 0 ? false : $count;
	}

	/**
	 * Translate array values if possible
	 * 
	 * @param array $array
	 * @throws InvalidArgumentException
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
	 * Formatted translator
	 * 
	 * @param string $target
	 * @return string
	 */
	public function ftranslate($target)
	{
		foreach ($this->data as $key => $value) {
			if (mb_stripos($target, $key, 0, self::CHARSET) !== false) {
				return str_replace($key, $value, $target);
			}
		}
		
		return $target;
	}

	/**
	 * Translates a string
	 * It's used as $i18n->translate('String from dictionary', array())
	 * 
	 * @param string $default Default message
	 * @param  string|array $placeholders Number of placeholder according to specified string
	 * @throws InvalidArgumentException if first argument $message wasn't string
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
			
			// Only strings and integers are supported
			if (is_string($argument) || is_numeric($argument)) {
				array_push($variables, $argument);
			}
		}
		
		// - We've done collection method variables here -- \\
		
		// The array we are dealing with
		$source =& $this->source;
		
		if (isset($source[$message])) {
			$message = vsprintf($source[$message], $variables);
			$this->appendProcessed($message);
			
			return $message;
		}
		
		$message = vsprintf($message, $variables);
		$this->appendProcessed($message);
		return $message;
	}
}

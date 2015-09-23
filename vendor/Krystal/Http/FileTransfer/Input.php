<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\FileTransfer;

use LogicException;
use InvalidArgumentException;
use UnexpectedValueException;
use RuntimeException;

final class Input implements InputInterface
{
	/**
	 * The local reference to $_FILES superglobal
	 * 
	 * @var array
	 */
	private $files = array();

	/**
	 * State initialization
	 * 
	 * @param array $files That must be $_FILES superglobal
	 * @throws \LogicException if maximal depth level of $_FILES exceeds
	 * @return void
	 */
	public function __construct(array $files)
	{
		if ($this->isValid($files)) {

			$this->files = $files;
			$this->prepare();

		} else {
			throw new LogicException('Invalid depth level of $_FILES supplied');
		}
	}

	/**
	 * Prepares an array, so that we'll be able to safely work with it
	 * 
	 * @return void
	 */
	private function prepare()
	{
		$this->remapAll();

		// Now remove empty elements
		$prepared = array();

		foreach ($this->files as $name => $files) {
			$prepared[$name] = $this->removeEmpty($files);
		}

		// Override with modified one
		$this->files = $prepared;
	}

	/**
	 * Remove empty values, i.e values with ERR_NO_FILE errors
	 * 
	 * @param array $files
	 * @return array
	 */
	private function removeEmpty(array $files)
	{
		$result = array();
		foreach ($files as $index => $array) {
			if (!empty($array['name']) && !empty($array['tmp_name'])) {
				$result[] = $array;
			}
		}

		return $result;
	}

	/**
	 * Checks whether $_FILES input array is valid
	 * That ensures that its nested level isn't too deep
	 * 
	 * @param array $target
	 * @throws \UnexpectedValueException if invalid input name supplied
	 * @return boolean True if input is valid, False if not
	 */
	private function isValid(array $target)
	{
		// When testing the array, we don't care about names, but values only
		$files = array_values($target);

		// Ensure that $_FILES isn't empty, before we even start doing anything
		if (empty($files)) {
			throw new UnexpectedValueException('Invalid input name supplied');
		}

		// An array is not sorted yet, so that we'd do naive nested level testing
		foreach ($files as $index => $value) {
			$target = $files[$index]['type'];
				
			// If its an array, then multi-upload assumed
			if (is_array($target)) {
				foreach ($target as $index => $value) {
					// Jeez, it can no longer be nested
					if (is_array($value)) {
						return false;
					}
				}
			}
		}

		return true;
	}

	/**
	 * Checks whether named input field is empty. If $name is null, then checks the whole array
	 * 
	 * @param string $name Optionally filtered by name
	 * @throws RuntimeException if $name isn't valid field
	 * @return boolean
	 */
	public function hasFiles($name = null)
	{
		if ($name !== null) {
			// Ensure the field exists
			if (!array_key_exists($name, $this->files)) {
				throw new RuntimeException(sprintf(
					'Attempted to access non-existing field %s', $name
				));
			}

			return !empty($this->files[$name]);

		} else {
			// Global checking if all values are not empty
			$target = array();

			foreach ($this->files as $name => $array) {
				if (!empty($array)) {
					array_push($target, $array);
				}
			}

			return count($target) !== 0;
		}
	}

	/**
	 * Return all files. Optionally filtered by named input
	 * 
	 * @param string $name Name filter
	 * @throws RuntimeException if $name isn't valid field
	 * @return object
	 */
	public function getFiles($name = null)
	{
		if ($name !== null) {
			if (!array_key_exists($name, $this->files)) {
				throw new RuntimeException(sprintf(
					'Attempted to access non-existing field %s', $name
				));
			}

			return $this->toObject($this->files[$name]);

		} else {
			// When returning all files
			$return = array();
			$names = array_keys($this->files);

			foreach ($names as $name) {
				$return[$name] = $this->toObject($this->files[$name]);
			}

			return $return;
		}
	}

	/**
	 * Converts an array to object
	 * 
	 * @param array $files
	 * @return array
	 */
	private function toObject(array $files)
	{
		$entities = array();

		foreach ($files as $index => $array) {
			$entity = new FileEntity();
			$entity->setType($array['type'])
				   ->setName($array['name'])
				   ->setTmpName($array['tmp_name'])
				   ->setSize($array['size'])
				   ->setError($array['error']);

			array_push($entities, $entity);
		}

		return $entities;
	}

	/**
	 * Sorts whole input array
	 * 
	 * @return void
	 */
	private function remapAll()
	{
		$names = array_keys($this->files);

		foreach ($names as $name) {
			$this->files[$name] = $this->remap($this->files[$name]);
		}
	}

	/**
	 * Remaps the initial array
	 * 
	 * @param string|array $source
	 * @return array
	 */
	private function remap($source)
	{
		if (is_array($source['size'])) {
			// This is what we are going to return
			$files = array();

			foreach ($source as $key => $array) {
				foreach ($array as $index => $value) {
					// Make sure it's not an array:
					if (!isset($files[$index]) || !is_array($files[$index])) {

						$files[$index] = array();
						// This key we want to assign right here
						// otherwise it will be not available inside "else" block
						$files[$index]['name'] = $value;

					} else {
						// Ensure the key does not exist
						if (!array_key_exists($key, $files[$index])) {
							// If so, append a new one
							$files[$index][$key] = $value;
						}
					}
				}
			}

			$source = $files;
			
		} else {
	
			// Not an array, then single upload assumed
			$source = array(0 => $source);
		}

		return $source;
	}
}

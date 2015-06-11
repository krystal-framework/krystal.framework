<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Text;

interface CsvManagerInterface
{
	/**
	 * Checks whether stack is empty
	 * 
	 * @return boolean
	 */
	public function isEmpty();

	/**
	 * Clears the stack
	 * 
	 * @return void
	 */
	public function flush();

	/**
	 * Count values
	 * 
	 * @param boolean $includeDuplicates Whether to include duplicate values count
	 * @return integer
	 */
	public function count($includeDuplicates = true);

	/**
	 * Checks if a value has duplicates in a sequence
	 * 
	 * @param string $target
	 * @throws RuntimeException if $target does not belong to the collection
	 * @return boolean
	 */
	public function hasDuplicates($target);

	/**
	 * Return duplicate count by a value
	 * 
	 * @param string $target
	 * @throws RuntimeException if attempted to read non-existing value
	 * @return integer
	 */
	public function getDuplicatesCount($target);

	/**
	 * Appends one more value
	 * 
	 * @param string $value
	 * @throws LogicException if $value contains a delimiter
	 * @return object $this
	 */
	public function append($value);

	/**
	 * Append collection
	 * 
	 * @param array $collection
	 * @return object $this
	 */
	public function appendCollection(array $collection);

	/**
	 * Checks whether value exists in a stack
	 * 
	 * @param string $value [...]
	 * @return boolean
	 */
	public function exists();

	/**
	 * Deletes a value from the stack
	 * 
	 * @param string $target
	 * @param boolean $keepDuplicates Whether to keep duplicate values
	 * @return void
	 */
	public function delete($target, $keepDuplicates = true);

	/**
	 * Returns value collection
	 * 
	 * @return array
	 */
	public function getCollection();

	/**
	 * Converts an array into a string
	 * 
	 * @param array $array
	 * @return string
	 */
	public function getAsString();

	/**
	 * Builds an array from a string
	 * 
	 * @param string $string
	 * @return void
	 */
	public function loadFromString($string);
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Filesystem;

interface MimeTypeGuesserInterface
{
	/**
	 * Finds associated type by its extension
	 * 
	 * @param string $extension
	 * @throws \RuntimeException if unknown extension supplied
	 * @return string
	 */
	public function getTypeByExtension($extension);

	/**
	 * Finds associated extension by its type
	 * 
	 * @param string $type
	 * @throws \RuntimeException if unknown type supplied
	 * @return string
	 */
	public function getExtensionByType($type);

	/**
	 * Returns full list
	 * 
	 * @param boolean $flip Whether to flip the returning array or not
	 * @return array
	 */
	public function getList($flip = false);

	/**
	 * Return extensions only
	 * 
	 * @return array
	 */
	public function getExtensions();

	/**
	 * Return types only
	 * 
	 * @return array
	 */
	public function getTypes();

	/**
	 * Check whether extension is known
	 * 
	 * @param string $extension
	 * @return boolean
	 */
	public function isValidExtension($extension);

	/**
	 * Check whether type is known
	 * 
	 * @param string $type
	 * @return boolean
	 */
	public function isValidType($type);

	/**
	 * Appends pair to the end of the stack
	 * 
	 * @param array $pair
	 * @return void
	 */
	public function append(array $pair);
}

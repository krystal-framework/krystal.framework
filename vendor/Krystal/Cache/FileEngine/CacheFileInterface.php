<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Cache\FileEngine;

interface CacheFileInterface
{
	/**
	 * Returns file content
	 * 
	 * @throws \LogicException If file hasn't been loaded before
	 * @return string
	 */
	public function getContent();

	/**
	 * Loads content from a file
	 * 
	 * @throws \RuntimeException When $autoCreate set to false and file doesn't exist
	 * @return array
	 */
	public function load();

	/**
	 * Writes data to the file
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function save(array $data);
}

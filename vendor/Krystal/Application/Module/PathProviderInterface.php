<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Module;

interface PathProviderInterface
{
	/**
	 * Returns configuration directory of current module
	 * 
	 * @return string
	 */
	public function getConfigDir();

	/**
	 * Appends provided filename to configuration's directory and returns it
	 * 
	 * @param string $file
	 * @return string
	 */
	public function getWithConfigDir($file);

	/**
	 * Returns translations directory of current module
	 * 
	 * @return string
	 */
	public function getTranslationsDir();

	/**
	 * Returns appended language's code and file's name with translations directory
	 * 
	 * @param string $language
	 * @param string $file
	 * @return string
	 */
	public function getWithTranslationsDir($language, $file);

	/**
	 * Returns assets directory
	 * 
	 * @return string
	 */
	public function getAssetsDir();
}

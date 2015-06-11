<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Module;

final class PathProvider implements PathProviderInterface
{
	/**
	 * Directory where modules are stored
	 * 
	 * @var string
	 */
	private $modulesDir;

	/**
	 * Current module's name
	 * 
	 * @var string
	 */
	private $moduleName;

	/**
	 * State initialization
	 * 
	 * @param string $modulesDir
	 * @param string $moduleName
	 * @return void
	 */
	public function __construct($modulesDir, $moduleName)
	{
		$this->modulesDir = $modulesDir;
		$this->moduleName = $moduleName;
	}

	/**
	 * Makes and returns current module path
	 * 
	 * @return string
	 */
	private function getModulePath()
	{
		return sprintf('%s/%s', $this->modulesDir, $this->moduleName);
	}

	/**
	 * Returns configuration directory of current module
	 * 
	 * @return string
	 */
	public function getConfigDir()
	{
		return sprintf('%s/Config', $this->getModulePath());
	}

	/**
	 * Appends provided filename to configuration's directory and returns it
	 * 
	 * @param string $file
	 * @return string
	 */
	public function getWithConfigDir($file)
	{
		return sprintf('%s/%s', $this->getConfigDir(), $file);
	}

	/**
	 * Returns translations directory of current module
	 * 
	 * @return string
	 */
	public function getTranslationsDir()
	{
		return sprintf('%s/Translations', $this->getModulePath());
	}

	/**
	 * Returns appended language's code and file's name with translations directory
	 * 
	 * @param string $language
	 * @param string $file
	 * @return string
	 */
	public function getWithTranslationsDir($language, $file)
	{
		return sprintf('%s/%s/%s', $this->getTranslationsDir(), $language, $file);
	}

	/**
	 * Returns assets directory
	 * 
	 * @return string
	 */
	public function getAssetsDir()
	{
		return sprintf('%s/Assets', $this->getModulePath());
	}
}

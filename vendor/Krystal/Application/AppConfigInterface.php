<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application;

interface AppConfigInterface
{
	/**
	 * Defines absolute path to data's directory
	 * 
	 * @param string $dataDir
	 * @return \Krystal\Application\AppConfig
	 */
	public function setDataDir($dataDir);

	/**
	 * Returns absolute path to data's directory
	 * 
	 * @return string
	 */
	public function getDataDir();

	/**
	 * Defines absolute path to temporary directory
	 * 
	 * @param string $tempDir
	 * @return \Krystal\Application\AppConfig
	 */
	public function setTempDir($tempDir);

	/**
	 * Returns absolute path to temporary directory
	 * 
	 * @return string
	 */
	public function getTempDir();

	/**
	 * Defines absolute path to cache directory
	 * 
	 * @param string $cacheDir
	 * @return \Krystal\Application\AppConfig
	 */
	public function setCacheDir($cacheDir);

	/**
	 * Returns absolute path to cache directory
	 * 
	 * @return string
	 */
	public function getCacheDir();

	/**
	 * Defines theme's name
	 * 
	 * @param string $theme
	 * @return \Krystal\Application\AppConfig
	 */
	public function setTheme($theme);

	/**
	 * Returns theme's name
	 * 
	 * @return string
	 */
	public function getTheme();

	/**
	 * Defines a directory for the current theme's name
	 * 
	 * @param string $themeDir
	 * @return \Krystal\Application\AppConfig
	 */
	public function setThemeDir($themeDir);

	/**
	 * Returns absolute path to the current theme's directory
	 * 
	 * @return string
	 */
	public function getThemeDir();

	/**
	 * Defines absolute path to a theme directory
	 * 
	 * @param string $themesDir
	 * @return \Krystal\Application\AppConfig
	 */
	public function setThemesDir($themesDir);

	/**
	 * Returns absolute path to a theme directory
	 * 
	 * @return string
	 */
	public function getThemesDir();

	/**
	 * Defines a absolute path to a directory where all uploading should be stored
	 * 
	 * @param string $uploadsDir
	 * @return \Krystal\Application\AppConfig
	 */
	public function setUploadsDir($uploadsDir);

	/**
	 * Returns absolute path for directory of uploads
	 * 
	 * @return string
	 */
	public function getUploadsDir();

	/**
	 * Defines URL path for uploads directory
	 * 
	 * @param string $uploadsUrl
	 * @return \Krystal\Application\AppConfig
	 */
	public function setUploadsUrl($uploadsUrl);

	/**
	 * Returns URL path to uploads directory
	 *  
	 * @return string
	 */
	public function getUploadsUrl();

	/**
	 * Defines root directory's absolute path
	 * 
	 * @param string $rootDir
	 * @return \Krystal\Application\AppConfig
	 */
	public function setRootDir($rootDir);

	/**
	 * Returns root directory's absolute path
	 * 
	 * @return string
	 */
	public function getRootDir();

	/**
	 * Defines root URL
	 * 
	 * @param string $rootUrl
	 * @return \Krystal\Application\AppConfig
	 */
	public function setRootUrl($rootUrl);

	/**
	 * Returns root URL
	 * 
	 * @return string
	 */
	public function getRootUrl();

	/**
	 * Defines absolute path to the module's directory
	 * 
	 * @param string $modulesDir
	 * @return \Krystal\Application\AppConfig
	 */
	public function setModulesDir($modulesDir);

	/**
	 * Returns absolute path to module's directory
	 * 
	 * @return string
	 */
	public function getModulesDir();

	/**
	 * Returns a directory path on file-system of particular module
	 * 
	 * @param string $module
	 * @return string
	 */
	public function getModuleUploadsDir($module);

	/**
	 * Defines application's default charset
	 * 
	 * @param string $charset
	 * @return \Krystal\Application\AppConfig
	 */
	public function setCharset($charset);

	/**
	 * Returns application's default charset
	 * 
	 * @return string
	 */
	public function getCharset();

	/**
	 * Defines application's default locale
	 * 
	 * @param string $locale
	 * @return \Krystal\Application\AppConfig
	 */
	public function setLocale($locale);

	/**
	 * Returns application's default locale
	 * 
	 * @return string
	 */
	public function getLocale();

	/**
	 * Defines application's default language
	 * 
	 * @param string $language
	 * @return \Krystal\Application\AppConfig
	 */
	public function setLanguage($language);

	/**
	 * Returns application's default language
	 * 
	 * @return string
	 */
	public function getLanguage();
}

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

final class AppConfig implements AppConfigInterface
{
    /**
     * Data directory
     * 
     * @var string
     */
    private $dataDir;

    /**
     * Temporary directory
     * 
     * @var string
     */
    private $tempDir;

    /**
     * Cache directory
     * 
     * @var string
     */
    private $cacheDir;

    /**
     * Current theme
     * 
     * @var string
     */
    private $theme;

    /**
     * Theme directory
     * 
     * @var string
     */
    private $themeDir;

    /**
     * Absolute path to a directory where all themes are stored
     * 
     * @var string
     */
    private $themesDir;

    /**
     * Absolute path to directory where all uploads must be stored
     * 
     * @var string
     */
    private $uploadsDir;

    /**
     * URL path to uploads directory
     * 
     * @var string
     */
    private $uploadsUrl;

    /**
     * Absolute path to a root directory
     * 
     * @var string
     */
    private $rootDir;

    /**
     * System's root URL
     * 
     * @var string
     */
    private $rootUrl;

    /**
     * Path to absolute directory for module's directory
     * 
     * @var string
     */
    private $modulesDir;

    /**
     * Default application's charset
     * 
     * @var string
     */
    private $charset;

    /**
     * Default application's locale
     * 
     * @var string
     */
    private $locale;

    /**
     * Default application's language
     * 
     * @var string
     */
    private $language;

    /**
     * Defines absolute path to data's directory
     * 
     * @param string $dataDir
     * @return \Krystal\Application\AppConfig
     */
    public function setDataDir($dataDir)
    {
        $this->dataDir = $dataDir;
        return $this;
    }

    /**
     * Returns absolute path to data's directory
     * 
     * @return string
     */
    public function getDataDir()
    {
        return $this->dataDir;
    }

    /**
     * Defines absolute path to temporary directory
     * 
     * @param string $tempDir
     * @return \Krystal\Application\AppConfig
     */
    public function setTempDir($tempDir)
    {
        $this->tempDir = $tempDir;
        return $this;
    }

    /**
     * Returns absolute path to temporary directory
     * 
     * @return string
     */
    public function getTempDir()
    {
        return $this->tempDir;
    }

    /**
     * Defines absolute path to cache directory
     * 
     * @param string $cacheDir
     * @return \Krystal\Application\AppConfig
     */
    public function setCacheDir($cacheDir)
    {
        $this->cacheDir = $cacheDir;
        return $this;
    }

    /**
     * Returns absolute path to cache directory
     * 
     * @return string
     */
    public function getCacheDir()
    {
        return $this->cacheDir;
    }

    /**
     * Defines theme's name
     * 
     * @param string $theme
     * @return \Krystal\Application\AppConfig
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
        return $this;
    }

    /**
     * Returns theme's name
     * 
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Defines a directory for the current theme's name
     * 
     * @param string $themeDir
     * @return \Krystal\Application\AppConfig
     */
    public function setThemeDir($themeDir)
    {
        $this->themeDir = $themeDir;
        return $this;
    }

    /**
     * Returns absolute path to the current theme's directory
     * 
     * @return string
     */
    public function getThemeDir()
    {
        return $this->themeDir;
    }

    /**
     * Defines absolute path to a theme directory
     * 
     * @param string $themesDir
     * @return \Krystal\Application\AppConfig
     */
    public function setThemesDir($themesDir)
    {
        $this->themesDir = $themesDir;
        return $this;
    }

    /**
     * Returns absolute path to a theme directory
     * 
     * @return string
     */
    public function getThemesDir()
    {
        return $this->themesDir;
    }

    /**
     * Defines a absolute path to a directory where all uploading should be stored
     * 
     * @param string $uploadsDir
     * @return \Krystal\Application\AppConfig
     */
    public function setUploadsDir($uploadsDir)
    {
        $this->uploadsDir = $uploadsDir;
        return $this;
    }

    /**
     * Returns absolute path for directory of uploads
     * 
     * @return string
     */
    public function getUploadsDir()
    {
        return $this->uploadsDir;
    }

    /**
     * Defines URL path for uploads directory
     * 
     * @param string $uploadsUrl
     * @return \Krystal\Application\AppConfig
     */
    public function setUploadsUrl($uploadsUrl)
    {
        $this->uploadsUrl = $uploadsUrl;
        return $this;
    }

    /**
     * Returns URL path to uploads directory
     *  
     * @return string
     */
    public function getUploadsUrl()
    {
        return $this->uploadsUrl;
    }

    /**
     * Defines root directory's absolute path
     * 
     * @param string $rootDir
     * @return \Krystal\Application\AppConfig
     */
    public function setRootDir($rootDir)
    {
        $this->rootDir = $rootDir;
        return $this;
    }

    /**
     * Returns root directory's absolute path
     * 
     * @return string
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }

    /**
     * Defines root URL
     * 
     * @param string $rootUrl
     * @return \Krystal\Application\AppConfig
     */
    public function setRootUrl($rootUrl)
    {
        $this->rootUrl = $rootUrl;
        return $this;
    }

    /**
     * Returns root URL
     * 
     * @return string
     */
    public function getRootUrl()
    {
        return $this->rootUrl;
    }

    /**
     * Defines absolute path to the module's directory
     * 
     * @param string $modulesDir
     * @return \Krystal\Application\AppConfig
     */
    public function setModulesDir($modulesDir)
    {
        $this->modulesDir = $modulesDir;
        return $this;
    }

    /**
     * Returns absolute path to module's directory
     * 
     * @return string
     */
    public function getModulesDir()
    {
        return $this->modulesDir;
    }

    /**
     * Returns a path to uploads directory of particular module
     * 
     * @param string $base
     * @param string $module
     * @return string
     */
    private function getModuleUploadsPath($base, $module)
    {
        return sprintf('%s/module/%s', $base, $module);
    }

    /**
     * Returns a directory path on file-system of particular module
     * 
     * @param string $module
     * @return string
     */
    public function getModuleUploadsDir($module)
    {
        return $this->getModuleUploadsPath($this->getUploadsDir(), $module);
    }

    /**
     * Defines application's default charset
     * 
     * @param string $charset
     * @return \Krystal\Application\AppConfig
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
        return $this;
    }

    /**
     * Returns application's default charset
     * 
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * Defines application's default locale
     * 
     * @param string $locale
     * @return \Krystal\Application\AppConfig
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * Returns application's default locale
     * 
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Defines application's default language
     * 
     * @param string $language
     * @return \Krystal\Application\AppConfig
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }
    
    /**
     * Returns application's default language
     * 
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }
}
<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\View\Resolver;

use Krystal\Http\RequestInterface;

final class ModuleResolver implements ResolverInterface
{
    /**
     * Request service
     * 
     * @var \Krystal\Http\RequestInterface
     */
    private $request;

    /**
     * The name of module directory
     * 
     * @var string
     */
    private $moduleDir;

    /**
     * Target module the theme belongs to
     * 
     * @var string
     */
    private $module;

    /**
     * Theme name
     * 
     * @var string
     */
    private $theme;

    /**
     * Base directory
     * 
     * @var string
     */
    private $baseDir = 'View/Template';

    /**
     * Assets directory
     * 
     * @var string
     */
    private $assetsDir = 'Assets';

    /**
     * An extension for view templates
     * 
     * @var string
     */
    private $extension = '.phtml';

    /**
     * State initialization
     * 
     * @param \Krystal\Http\RequestInterface $request
     * @param string $moduleDir Full path to module directory on the file-system
     * @param string $module Current module name which is being executed
     * @param string $theme Theme name
     * @return void
     */
    public function __construct(RequestInterface $request, $moduleDir, $module, $theme)
    {
        $this->request = $request;
        $this->moduleDir = $moduleDir;
        $this->module = $module;
        $this->theme = $theme;
    }

    /**
     * Overrides directory name for assets
     * 
     * @param string $assetsDir
     * @return \Krystal\Application\View\Resolver\ModuleResolver
     */
    public function setAssetsDir($assetsDir)
    {
        $this->assetsDir = $assetsDir;
        return $this;
    }

    /**
     * Overrides a base directory
     * 
     * @param string $baseDir
     * @return \Krystal\Application\View\Resolver\ModuleResolver
     */
    public function setBaseDir($baseDir)
    {
        $this->baseDir = $baseDir;
        return $this;
    }

    /**
     * Overrides default theme
     * 
     * @param string $theme
     * @return \Krystal\Application\View\Resolver\ModuleResolver
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
        return $this;
    }

    /**
     * Overrides default module
     * 
     * @param string $module
     * @return \Krystal\Application\View\Resolver\ModuleResolver
     */
    public function setModule($module)
    {
        $this->module = $module;
        return $this;
    }

    /**
     * Overrides default extension
     * 
     * @param string $extension
     * @return \Krystal\Application\View\Resolver\ModuleResolver
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * Returns current theme
     * 
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Returns asset path by module and its nested path
     * 
     * @param string $path
     * @param string $module Optionally default module can be replaced by another one
     * @param boolean $absolute Whether returned path should be absolute or relative
     * @param boolean $fromAssets Whether to use assets directory or theme's internal one
     * @return string
     */
    public function getWithAssetPath($path, $module, $absolute, $fromAssets)
    {
        if ($absolute === true) {
            $url = $this->request->getBaseUrl();
        } else {
            $url = null;
        }

        // If module isn't provided, then current one used by default
        if (is_null($module)) {
            $module = $this->module;
        }

        if ($fromAssets !== false) {
            $dir = $this->assetsDir;
            $theme = null;
        } else {
            $dir = $this->baseDir;
            $theme = $this->theme;
        }

        return $url.sprintf('%s/%s', $this->resolveWith($dir, $this->request->getBaseUrl(), $module, $theme), $path);
    }

    /**
     * Returns a theme path appending required filename
     * 
     * @param string $filename
     * @return string
     */
    public function getWithThemePath($filename)
    {
        return sprintf('%s/%s', $this->resolve(), $filename);
    }

    /**
     * Resolves a base path
     * 
     * @param string $theme Optionally a theme can be overridden
     * @return string
     */
    public function resolve($theme = null)
    {
        if (is_null($theme)) {
            $theme = $this->theme;
        }

        return sprintf('%s/%s/%s/%s', $this->moduleDir, $this->module, $this->baseDir, $theme);
    }

    /**
     * Returns full path to a file by its name
     * 
     * @param string $name File's basename
     * @param string $module Module name
     * @return string
     */
    public function getFilePathByName($name, $module = null)
    {
        if (is_null($module)) {
            $module = $this->module;
        }

        $base = $this->resolveWith($this->baseDir, dirname($this->moduleDir), $module, $this->theme);
        return $base.\DIRECTORY_SEPARATOR.$name.$this->extension;
    }

    /**
     * Resolves with a path
     * 
     * @param string $dir Base directory when assets are stored
     * @param string $path
     * @param string $module
     * @param string $theme
     * @return string
     */
    private function resolveWith($dir, $path, $module, $theme = null)
    {
        if ($theme !== null) {
            return sprintf('%s/module/%s/%s/%s', $path, $module, $dir, $theme);
        } else {
            return sprintf('%s/module/%s/%s', $path, $module, $dir);
        }
    }
}

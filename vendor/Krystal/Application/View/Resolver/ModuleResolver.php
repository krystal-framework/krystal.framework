<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
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
	private $baseDir;

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
	 * @param string $moduleDir
	 * @param string $module
	 * @param string $theme Theme name
	 * @param string $baseDir
	 * @return void
	 */
	public function __construct(RequestInterface $request, $moduleDir, $module, $theme, $baseDir = 'View/Template')
	{
		$this->request = $request;
		$this->moduleDir = $moduleDir;
		$this->module = $module;
		$this->theme = $theme;
		$this->baseDir = $baseDir;
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
	 * Returns asset path by module and its nested path
	 * 
	 * @param string $path
	 * @param string $module Optionally default module can be replaced by another one
	 * @param boolean $absolute Whether returned path should be absolute or relative
	 * @return string
	 */
	public function getWithAssetPath($path, $module, $absolute)
	{
		if ($absolute === true) {
			$url = $this->request->getUrl();
		} else {
			$url = null;
		}

		// If module isn't provided, then current one used by default
		if (is_null($module)) {
			$module = $this->module;
		}
		
		return $url . $this->resolveAsUrlWith($path, $this->module);
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
	 * @return string
	 */
	public function getFilePathByName($name, $module = null)
	{
		if (is_null($module)) {
			$module = $this->module;
		}

		return $this->resolveAsPath($module) . \DIRECTORY_SEPARATOR . $name . $this->extension;
	}

	/**
	 * Resolves a path and appends a target one
	 * 
	 * @param string $path
	 * @return string
	 */
	private function resolveWithPath($path)
	{
		return sprintf('%s/%s', $this->resolve(), $path);
	}

	/**
	 * Resolves as a path on the file-system
	 * 
	 * @return string
	 */
	private function resolveAsPath($module)
	{
		return $this->resolveWith(dirname($this->moduleDir), $module);
	}

	/**
	 * Resolves as URL path
	 * 
	 * @return string
	 */
	private function resolveAsUrl($module)
	{
		return $this->resolveWith($this->request->getUrl(), $module);
	}

	/**
	 * Resolves with URL with appends
	 * 
	 * @param string $path
	 * @return string
	 */
	private function resolveAsUrlWith($path, $module)
	{
		return sprintf('%s/%s', $this->resolveAsUrl($module), $path);
	}

	/**
	 * Resolves with a path
	 * 
	 * @param string $path
	 * @return string
	 */
	private function resolveWith($path, $module)
	{
		return sprintf('%s/%s', $path, $this->getBasePathByModule($module));
	}

	/**
	 * Returns a base path which is common for both resolvers
	 * 
	 * @param string $module
	 * @return string
	 */
	private function getBasePathByModule($module)
	{
		return sprintf('module/%s/%s/%s', $module, $this->baseDir, $this->theme);
	}
}

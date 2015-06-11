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

final class Module implements ResolverInterface
{
	/**
	 * @var string
	 */
	private $moduleDir;

	/**
	 * @var string
	 */
	private $module;

	/**
	 * @var string
	 */
	private $theme;

	/**
	 * @var string
	 */
	private $baseDir;
	
	/**
	 * Files extension
	 * 
	 * @var string
	 */
	private $extension = '.phtml';

	/**
	 * State initialization
	 * 
	 * @return void
	 */
	public function __construct($request, $moduleDir, $module, $theme, $baseDir = 'View/Template')
	{
		$this->request = $request;
		$this->moduleDir = $moduleDir;
		$this->module = $module;
		$this->theme = $theme;
		$this->baseDir = $baseDir;
	}

	/**
	 * Overrides default extension
	 * 
	 * @param string $extension
	 * @return \Krystal\Application\View\Resolver\Module
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
	 * Resolves a base path
	 * 
	 * @return string
	 */
	public function resolve()
	{
		return sprintf('%s/%s/%s/%s', $this->moduleDir, $this->module, $this->baseDir, $this->theme);
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
	 *
	 */
	private function resolveAsBaseWith()
	{
		
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

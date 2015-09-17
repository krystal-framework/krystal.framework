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
 
interface ResolverInterface
{
	/**
	 * Defines/overrides a base directory
	 * 
	 * @param string $baseDir
	 * @return \Krystal\Application\View\Resolver\ModuleResolver
	 */
	public function setBaseDir($baseDir);

	/**
	 * Overrides default theme
	 * 
	 * @param string $theme
	 * @return \Krystal\Application\View\Resolver\ModuleResolver
	 */
	public function setTheme($theme);

	/**
	 * Overrides default module
	 * 
	 * @param string $module
	 * @return \Krystal\Application\View\Resolver\ModuleResolver
	 */
	public function setModule($module);

	/**
	 * Overrides default extension
	 * 
	 * @param string $extension
	 * @return \Krystal\Application\View\Resolver\ModuleResolver
	 */
	public function setExtension($extension);

	/**
	 * Returns asset path by module and its nested path
	 * 
	 * @param string $path
	 * @param string $module Optionally default module can be replaced by another one
	 * @param boolean $absolute Whether returned path should be absolute or relative
	 * @return string
	 */
	public function getWithAssetPath($path, $module, $absolute);

	/**
	 * Returns a theme path appending required filename
	 * 
	 * @param string $filename
	 * @return string
	 */
	public function getWithThemePath($filename);

	/**
	 * Resolves a base path
	 * 
	 * @param string $theme Optionally a theme can be overridden
	 * @return string
	 */
	public function resolve($theme = null);

	/**
	 * Returns full path to a file by its name
	 * 
	 * @param string $name File's basename
	 * @return string
	 */
	public function getFilePathByName($name, $module = null);
}

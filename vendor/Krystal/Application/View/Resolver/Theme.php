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
/**
 * This template resolver is responsible for grabbing templates from themes
 */
final class Theme implements ResolverInterface
{
	/**
	 * Themes directory
	 * 
	 * @var string
	 */
	private $themeDir;

	/**
	 * Current theme
	 * 
	 * @var string
	 */
	private $theme;

	/**
	 * State initialization
	 * 
	 * @param string $themeDir
	 * @param string $theme
	 * @return void
	 */
	public function __construct($themeDir, $theme)
	{
		$this->themeDir = $themeDir;
		$this->theme = $theme;
	}

	/**
	 * Returns defined theme
	 * 
	 * @return string
	 */
	public function getTheme()
	{
		return $this->theme;
	}

	/**
	 * 
	 * @return string
	 */
	public function getBlocksDir()
	{
	}

	/**
	 * Resolves a template path
	 * 
	 * @return string
	 */
	public function resolve()
	{
		return sprintf('%s/%s', $this->themeDir, $this->theme);
	}
}

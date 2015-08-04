<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\View;

use Krystal\Application\View\Resolver\ResolverInterface;

interface ViewManagerInterface
{
	/**
	 * Returns translator's instance
	 * 
	 * @return \Krystal\I18n\Translator $translator
	 */
	public function getTranslator();

	/**
	 * Returns plugin bag
	 * 
	 * @return \Krystal\Application\View\PluginBag
	 */
	public function getPluginBag();

	/**
	 * Returns prepared bread-crumb bag
	 * 
	 * @return \Krystal\Application\View\BreadcrumbBag
	 */
	public function getBreadcrumbBag();

	/**
	 * Returns block bag
	 * 
	 * @return \Krystal\Application\View\BlockBag
	 */
	public function getBlockBag();

	/**
	 * Adds a variable
	 * 
	 * @param string $name Variable name in view
	 * @param mixed $variable A variable itself
	 * @return \Krystal\Application\View\ViewManager
	 */
	public function addVariable($name, $variable);

	/**
	 * Appends collection of variables
	 * 
	 * @param array $variables Collection of variables
	 * @return \Krystal\Application\View\ViewManager
	 */
	public function addVariables(array $variables);

	/**
	 * Returns current theme
	 * 
	 * @return string
	 */
	public function getTheme();

	/**
	 * Defines whether output compression should be done
	 * 
	 * @param boolean $compress
	 * @return void
	 */
	public function setCompress($compress);

	/**
	 * Generates URL by known controller's syntax and optional arguments
	 * This should be used inside templates only
	 * 
	 * @return string
	 */
	public function url();

	/**
	 * Sets/Overrides default template resolver
	 * 
	 * @param \Krystal\Application\View\Resolver\ResolverInterface $resolver Any resolver that implements this interface
	 * @return \Krystal\Application\View\ViewManager
	 */
	public function setResolver(ResolverInterface $resolver);

	/**
	 * Returns view resolver
	 * 
	 * @return \Krystal\Application\View\Resolver\ResolverInterface
	 */
	public function getResolver();

	/**
	 * Prints a string
	 * 
	 * @param string $message
	 * @param boolean $translate Whether to translate a string
	 * @return void
	 */
	public function show($message, $translate = true);

	/**
	 * Generates a full path to an asset
	 * 
	 * @param string $asset
	 * @param boolean $relative
	 * @return string
	 */
	public function asset($asset, $module = null, $absolute = false);

	/**
	 * Defines global template's layout
	 * 
	 * @param string $layout Just a basename of that layout inside theme's folder
	 * @return \Krystal\Application\View\ViewManager
	 */
	public function setLayout($layout, $module = null);

	/**
	 * Cancels defined layout if present
	 * 
	 * @return \Krystal\Application\View\ViewManager
	 */
	public function disableLayout();

	/**
	 * Checks whether global layout has been defined before
	 * 
	 * @return boolean
	 */
	public function hasLayout();

	/**
	 * Checks whether framework-compliant template file exists
	 * 
	 * @param string $template
	 * @return boolean
	 */
	public function templateExists($template);

	/**
	 * Passes variables and renders a template. If there's attached layout, then renders it with that layout
	 * 
	 * @param string $template Template's name without extension in themes directory
	 * @param array $vars
	 * @throws \RuntimeException if wrong template file provided
	 * @return string
	 */
	public function render($template, array $vars = array());

	/**
	 * Load several blocks at once
	 * 
	 * @param array $blocks
	 * @return void
	 */
	public function loadBlocks(array $blocks);

	/**
	 * Loads a block
	 * 
	 * @param string $name
	 * @param array $vars Additional variables if needed
	 * @return void
	 */
	public function loadBlock($name, array $vars = array());

	/**
	 * Translates a string
	 * 
	 * @param string $message
	 * @return string
	 */
	public function translate();
}

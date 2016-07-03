<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\View;

interface ViewManagerInterface
{
    /**
     * Returns translator's instance
     * 
     * @return \Krystal\I18n\Translator
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
     * Returns partial bag
     * 
     * @return \Krystal\Application\View\PartialBag
     */
    public function getPartialBag();

    /**
     * Defines target module
     * 
     * @param string $module
     * @return \Krystal\Application\View\ViewManager
     */
    public function setModule($module);

    /**
     * Returns target module
     * 
     * @return string
     */
    public function getModule();

    /**
     * Defines target theme
     * 
     * @param string $theme
     * @return \Krystal\Application\View\ViewManager
     */
    public function setTheme($theme);

    /**
     * Returns current theme
     * 
     * @return string
     */
    public function getTheme();

    /**
     * Defines global template's layout
     * 
     * @param string $layout Template name
     * @param string $layoutModule Just a basename of that layout inside theme's folder
     * @return \Krystal\Application\View\ViewManager
     */
    public function setLayout($layout, $layoutModule = null);

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
     * Defines whether output compression should be done
     * 
     * @param boolean $compress
     * @return \Krystal\Application\View\ViewManager
     */
    public function setCompress($compress);

    /**
     * Returns a variable
     * 
     * @param string $var Variable name
     * @param mixed $default Default value to be returned in case a variable doesn't exist
     * @return mixed
     */
    public function getVariable($var, $default = false);

    /**
     * Adds a variable
     * 
     * @param string $name Variable name in view
     * @param mixed $value A variable itself
     * @return \Krystal\Application\View\ViewManager
     */
    public function addVariable($name, $value);

    /**
     * Appends collection of variables
     * 
     * @param array $variables Collection of variables
     * @return \Krystal\Application\View\ViewManager
     */
    public function addVariables(array $variables);

    /**
     * Determines whether there's at least one defined variable
     * 
     * @return boolean
     */
    public function hasVariables();

    /**
     * Checks whether a variable exist
     * 
     * @param string $var
     * @return boolean
     */
    public function hasVariable($var);

    /**
     * Generates URL by known controller's syntax and optional arguments
     * This should be used inside templates only
     * 
     * @return string
     */
    public function url();

    /**
     * Prints a string
     * 
     * @param string $message
     * @param boolean $translate Whether to translate a string
     * @return void
     */
    public function show($message, $translate = true);

    /**
     * Returns a theme path appending required filename
     * 
     * @param string $filename
     * @return string
     */
    public function getWithThemePath($filename);

    /**
     * Creates theme URL
     * 
     * @param string $module
     * @param string $theme
     * @return string
     */
    public function createThemeUrl($module = null, $theme = null);

    /**
     * Resolves a base path
     * 
     * @param string $module
     * @param string $theme Optionally a theme can be overridden
     * @return string
     */
    public function createThemePath($module = null, $theme = null);

    /**
     * Creates URL for asset
     * 
     * @param string $module
     * @param string $path Optional path to be appended
     * @return string
     */
    public function createAssetUrl($module = null, $path = null);

    /**
     * Generates a path to module asset file
     * 
     * @param string $path The target asset path
     * @param string $module Optionally module name can be overridden. By default the current is used
     * @param boolean $absolute Whether path must be absolute or not
     * @return string
     */
    public function moduleAsset($asset, $module = null, $absolute = false);

    /**
     * Generates a full path to an asset
     * 
     * @param string $asset Path to the target asset
     * @param string $module
     * @param boolean $absolute Whether path must be absolute or not
     * @return string
     */
    public function asset($asset, $module = null, $absolute = false);

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
     * Renders a template with custom Module and its theme
     * 
     * @param string $module
     * @param string $theme Theme directory name
     * @param string $template Template file to be rendered
     * @param array $vars Variables to be passed to a template
     * @return string
     */
    public function renderRaw($module, $theme, $template, array $vars = array());

    /**
     * Load several partials at once
     * 
     * @param array $partials
     * @return void
     */
    public function loadPartials(array $partials);

    /**
     * Loads partial template
     * 
     * @param string $name
     * @param array $vars Additional variables if needed
     * @return void
     */
    public function loadPartial($name, array $vars = array());

    /**
     * Translates a string
     * 
     * @param string $message
     * @return string
     */
    public function translate();

    /**
     * Translates array values
     * 
     * @param array $messages
     * @return array
     */
    public function translateArray(array $messages);
}

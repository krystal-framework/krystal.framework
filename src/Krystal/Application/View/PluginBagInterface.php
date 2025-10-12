<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\View;

interface PluginBagInterface
{
    /**
     * Clear all scripts
     * 
     * @return \Krystal\Application\View\PluginBag
     */
    public function clearScripts();

    /**
     * Clear all stylesheets
     * 
     * @return \Krystal\Application\View\PluginBag
     */
    public function clearStylesheets();

    /**
     * Appends a stylesheet
     * 
     * @param string $stylesheet
     * @return \Krystal\Application\View\PluginBag
     */
    public function appendStylesheet($stylesheet);

    /**
     * Appends a collection of stylesheets
     * 
     * @param array $stylesheets
     * @return \Krystal\Application\View\PluginBag
     */
    public function appendStylesheets(array $stylesheets);

    /**
     * Returns registered all stylesheets
     * 
     * @return array
     */
    public function getStylesheets();

    /**
     * Appends a script
     * 
     * @param string $script
     * @return \Krystal\Application\View\PluginBag
     */
    public function appendScript($script);

    /**
     * Appends a collection of scripts
     * 
     * @param array $scripts
     * @return \Krystal\Application\View\PluginBag
     */
    public function appendScripts(array $scripts);

    /**
     * Appends last script
     * 
     * @param string $script
     * @return \Krystal\Application\View\PluginBag
     */
    public function appendLastScript($script);

    /**
     * Appends a collection of last scripts
     * 
     * @param array $scripts
     * @return \Krystal\Application\View\PluginBag
     */
    public function appendLastScripts(array $scripts);

    /**
     * Returns all registered scripts
     * 
     * @return array
     */
    public function getScripts();

    /**
     * Registers plugin collection
     * 
     * @return \Krystal\Application\View\PluginBag
     */
    public function register(array $collection);

    /**
     * Loads plugins or a single plugin
     * 
     * @param string|array $name
     * @return \Krystal\Application\View\PluginBag
     */
    public function load($plugins);
}

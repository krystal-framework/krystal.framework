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

final class PluginBag implements PluginBagInterface
{
    /**
     * All loaded plugins
     * 
     * @var array
     */
    private $plugins = array();

    /**
     * All scripts
     * 
     * @var array
     */
    private $scripts = array();

    /**
     * A collection of scripts that must be always loaded last
     * 
     * @var array
     */
    private $lastScripts = array();

    /**
     * A collection of stylesheet files that must be always loaded last
     * 
     * @var array
     */
    private $lastStylesheets = array();

    /**
     * All stylesheets
     * 
     * @var array
     */
    private $stylesheets = array();

    /**
     * Clear all scripts
     * 
     * @return \Krystal\Application\View\PluginBag
     */
    public function clearScripts()
    {
        $this->scripts = array();
        $this->lastScripts = array();

        return $this;
    }

    /**
     * Clear all stylesheets
     * 
     * @return \Krystal\Application\View\PluginBag
     */
    public function clearStylesheets()
    {
        $this->stylesheets = array();
        return $this;
    }

    /**
     * Appends a unit
     * 
     * @param string $unit A path to unit
     * @param array $stack Data container
     * @param boolean $once Whether to append the same resource twice (i.e don't append if appended before)
     * @return \Krystal\Application\View\PluginBag
     */
    private function appendInternal($unit, array &$stack, $once)
    {
        $unit = $this->normalizeAssetPath($unit);

        // If it's already in stack, just ignore this call
        if ($once === true && in_array($unit, $stack)) {
            return $this;
        }

        // Otherwise, push
        array_push($stack, $unit);
        return $this;
    }

    /**
     * Replaces a module path inside provided path
     * 
     * @param string $path Target path
     * @return string
     */
    private function normalizeAssetPath($path)
    {
        // Do nothing with URL (to avoid issues in case URL string contains @)
        if (filter_var($path, \FILTER_VALIDATE_URL)) {
            return $path;
        }

        $pattern = '~@(\w+)~';
        $replacement = sprintf('/%s/$1/%s', ViewManager::TEMPLATE_PARAM_MODULES_DIR, ViewManager::TEMPLATE_PARAM_ASSETS_DIR);

        return preg_replace($pattern, $replacement, $path);
    }

    /**
     * Appends a stylesheet
     * 
     * @param string $stylesheet
     * @param boolean $once Whether to append the same resource twice (i.e don't append if appended before)
     * @return \Krystal\Application\View\PluginBag
     */
    public function appendStylesheet($stylesheet, $once = true)
    {
        return $this->appendInternal($stylesheet, $this->stylesheets, $once);
    }

    /**
     * Appends a collection of stylesheets
     * 
     * @param array $stylesheets
     * @param boolean $once Whether to append the same resource twice (i.e don't append if appended before)
     * @return \Krystal\Application\View\PluginBag
     */
    public function appendStylesheets(array $stylesheets, $once = true)
    {
        foreach ($stylesheets as $stylesheet) {
            $this->appendStylesheet($stylesheet, $once);
        }

        return $this;
    }

    /**
     * Returns registered all stylesheets
     * 
     * @return array
     */
    public function getStylesheets()
    {
        return array_merge($this->stylesheets, $this->lastStylesheets);
    }

    /**
     * Appends last stylsheet
     * 
     * @param string $stylesheet
     * @param boolean $once Whether to append the same resource twice (i.e don't append if appended before)
     * @return \Krystal\Application\View\PluginBag
     */
    public function appendLastStylesheet($stylesheet, $once = true)
    {
        return $this->appendInternal($stylesheet, $this->lastStylesheets, $once);
    }

    /**
     * Append last stylesheet files
     * 
     * @param array $stylesheets
     * @param boolean $once Whether to append the same resource twice (i.e don't append if appended before)
     * @return \Krystal\Application\View\PluginBag
     */
    public function appendLastStylesheets(array $stylesheets, $once = true)
    {
        foreach ($stylesheets as $stylesheet) {
            $this->appendLastStylesheet($stylesheet, $once);
        }

        return $this;
    }

    /**
     * Appends a script
     * 
     * @param string $script
     * @param boolean $once Whether to append the same resource twice (i.e don't append if appended before)
     * @return \Krystal\Application\View\PluginBag
     */
    public function appendScript($script, $once = true)
    {
        return $this->appendInternal($script, $this->scripts, $once);
    }

    /**
     * Appends a collection of scripts
     * 
     * @param array $scripts
     * @param boolean $once Whether to append the same resource twice (i.e don't append if appended before)
     * @return \Krystal\Application\View\PluginBag
     */
    public function appendScripts(array $scripts, $once = true)
    {
        foreach ($scripts as $script) {
            $this->appendScript($script, $once);
        }

        return $this;
    }

    /**
     * Appends last script
     * 
     * @param string $script
     * @param boolean $once Whether to append the same resource twice (i.e don't append if appended before)
     * @return \Krystal\Application\View\PluginBag
     */
    public function appendLastScript($script, $once = true)
    {
        return $this->appendInternal($script, $this->lastScripts, $once);
    }

    /**
     * Appends a collection of last scripts
     * 
     * @param array $scripts
     * @param boolean $once Whether to append the same resource twice (i.e don't append if appended before)
     * @return \Krystal\Application\View\PluginBag
     */
    public function appendLastScripts(array $scripts, $once = true)
    {
        foreach ($scripts as $script) {
            $this->appendLastScript($script, $once);
        }

        return $this;
    }

    /**
     * Returns all registered scripts
     * 
     * @return array
     */
    public function getScripts()
    {
        return array_merge($this->scripts, $this->lastScripts);
    }

    /**
     * Registers plugin collection
     * 
     * @return \Krystal\Application\View\PluginBag
     */
    public function register(array $collection)
    {
        foreach ($collection as $name => $data) {
            $this->plugins[$name] = $data;
        }

        return $this;
    }

    /**
     * Loads plugins or a single plugin
     * 
     * @param string|array $name
     * @return \Krystal\Application\View\PluginBag
     */
    public function load($plugins)
    {
        if (!is_array($plugins)) {
            $plugins = (array) $plugins;
        }

        foreach ($plugins as $plugin) {
            if (!isset($this->plugins[$plugin])) {
                trigger_error(sprintf('Attempted to load non-existing plugin %s', $plugin));
                return false;
            }

            if (isset($this->plugins[$plugin]['scripts'])) {
                $this->appendScripts($this->plugins[$plugin]['scripts']);
            }

            if (isset($this->plugins[$plugin]['stylesheets'])) {
                $this->appendStylesheets($this->plugins[$plugin]['stylesheets']);
            }
        }

        return $this;
    }
}

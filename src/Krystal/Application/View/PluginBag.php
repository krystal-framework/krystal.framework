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
     * Replaces a module path inside provided path
     * 
     * @param string $path Target path
     * @return string
     */
    private function normalizeAssetPath($path)
    {
        $pattern = '~@(\w+)~';
        $replacement = sprintf('/%s/$1/%s', ViewManager::TEMPLATE_PARAM_MODULES_DIR, ViewManager::TEMPLATE_PARAM_ASSETS_DIR);

        return preg_replace($pattern, $replacement, $path);
    }

    /**
     * Appends a stylesheet
     * 
     * @param string $stylesheet
     * @return \Krystal\Application\View\PluginBag
     */
    public function appendStylesheet($stylesheet)
    {
        $stylesheet = $this->normalizeAssetPath($stylesheet);

        array_push($this->stylesheets, $stylesheet);
        return $this;
    }

    /**
     * Appends a collection of stylesheets
     * 
     * @param array $stylesheets
     * @return \Krystal\Application\View\PluginBag
     */
    public function appendStylesheets(array $stylesheets)
    {
        foreach ($stylesheets as $stylesheet) {
            $this->appendStylesheet($stylesheet);
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
        return $this->stylesheets;
    }

    /**
     * Appends a script
     * 
     * @param string $script
     * @return \Krystal\Application\View\PluginBag
     */
    public function appendScript($script)
    {
        $script = $this->normalizeAssetPath($script);

        array_push($this->scripts, $script);
        return $this;
    }

    /**
     * Appends a collection of scripts
     * 
     * @param array $scripts
     * @return \Krystal\Application\View\PluginBag
     */
    public function appendScripts(array $scripts)
    {
        foreach ($scripts as $script) {
            $this->appendScript($script);
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
        return $this->scripts;
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

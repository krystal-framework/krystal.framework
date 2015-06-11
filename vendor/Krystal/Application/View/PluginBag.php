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
	 * Appends a stylesheet
	 * 
	 * @param string $stylesheet
	 * @return \Krystal\Application\View\PluginBag
	 */
	public function appendStylesheet($stylesheet)
	{
		array_push($this->stylesheets, $stylesheet);
		return $this;
	}

	/**
	 * Append style sheet
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
	 * Return all stylesheets
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
		array_push($this->scripts, $script);
		return $this;
	}

	/**
	 * Append scripts
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
	 * Return scripts
	 * 
	 * @return array
	 */
	public function getScripts()
	{
		return $this->scripts;
	}
	
	//
	public function clear()
	{
		
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

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
	 * State initialization
	 * 
	 * @param \Krystal\Application\View\AssetPathInterface
	 * @return void
	 */
	public function __construct(AssetPathInterface $assetPath)
	{
		$this->assetPath = $assetPath;
	}

	/**
	 * Appends a stylesheet
	 * 
	 * @param string $stylesheet
	 * @return \Krystal\Application\View\PluginBag
	 */
	public function appendStylesheet($stylesheet)
	{
		$stylesheet = $this->assetPath->replace($stylesheet);

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
		$script = $this->assetPath->replace($script);

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

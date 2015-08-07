<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Module;

use Krystal\Application\AppConfigInterface;
use Krystal\Application\Module\Loader\LoaderInterface;
use Krystal\InstanceManager\ServiceLocator;
use Krystal\Application\AppConfig;
use RuntimeException;

final class ModuleManager implements ModuleManagerInterface
{
	/**
	 * Current modules
	 * 
	 * @var array
	 */
	private $modules = array();

	/**
	 * Module loader
	 * 
	 * @var \Krystal\Application\Module\Loader\LoaderInterface
	 */
	private $loader;

	/**
	 * Only loaded modules
	 * 
	 * @var array
	 */
	private $loaded = array();

	/**
	 * All module routes
	 * 
	 * @var array
	 */
	private $routes = array();

	/**
	 * Message translations
	 * 
	 * @var array
	 */
	private $translations = array();

	/**
	 * Application config container
	 * 
	 * @var \Krystal\Application\AppConfigInterface
	 */
	private $appConfig;

	/**
	 * Registered services
	 * 
	 * @var array
	 */
	private $services = array();

	/**
	 * Module config class that represents basic data in module
	 * No need for an extension, because it will be included by the PSR-0 autoloader automatically
	 * 
	 * @const string
	 */
	const MODULE_CONFIG_FILE = 'Module';

	/**
	 * State initialization
	 * 
	 * @param \Krystal\Application\Module\Loader\LoaderInterface $loader Any loader that implements this interface
	 * @param array $services
	 * @param \Krystal\Application\AppConfigInterface $appConfig
	 * @return void
	 */
	public function __construct(LoaderInterface $loader, array $services, AppConfigInterface $appConfig)
	{
		$this->loader = $loader;
		$this->services = $services;
		$this->appConfig = $appConfig;
	}

	/**
	 * Initializes the module manager
	 * 
	 * @throws \RuntimeException If no modules found
	 * @return void
	 */
	public function initialize()
	{
		$modules = $this->loader->getModules();

		if (empty($modules)) {
			throw new RuntimeException('No modules found. Initialization halted');
		} else {
			$this->loadAll($modules);
			$this->modules = $modules;
		}
	}

	/**
	 * Loads all modules
	 * 
	 * @param array $modules Modules to load
	 * @return void
	 */
	private function loadAll($modules)
	{
		array_walk($modules, array($this, 'loadModuleByName'));
	}

	/**
	 * Returns module instance by its name
	 * 
	 * @param object $name
	 * @return object
	 */
	public function getModule($name)
	{
		if ($this->isLoaded($name)) {
			return $this->loaded[$name];
		} else {
			return $this->loadModuleByName($name);
		}
	}

	/**
	 * Checks whether module is loaded
	 * 
	 * @param string $name Target module name
	 * @return boolean
	 */
	public function isLoaded($name)
	{
		return isset($this->loaded[$name]);
	}

	/**
	 * Returns an array of loaded module names
	 * 
	 * @return array
	 */
	public function getLoadedModuleNames()
	{
		return array_keys($this->loaded);
	}

	/**
	 * Returns collection of loaded modules
	 * 
	 * @return array
	 */
	public function getLoadedModules()
	{
		return $this->loaded;
	}

	/**
	 * Prepends module name to each route
	 * 
	 * @param string $module
	 * @param array $routes
	 * @return array
	 */
	private function prepareRoutes($module, array $routes)
	{
		$result = array();
		
		foreach ($routes as $uriTemplate => $options) {
			// Controller is the special case
			if (isset($options['controller'])) {
				// Override with module-compliant
				$options['controller'] = sprintf('%s:%s', $this->grabModuleName($module), $options['controller']);
			}
			
			$result[$uriTemplate] = $options;
		}
		
		return $result;
	}

	/**
	 * Grabs module name from its provided namespace
	 * 
	 * @param string $ns
	 * @return string
	 */
	private function grabModuleName($ns)
	{
		return substr($ns, 0, strpos($ns, '\\'));
	}

	/**
	 * Loads a module by its name
	 * 
	 * @param string $name Module name
	 * @throws \RuntimeException if attempted to load non-existing module
	 * @return Module
	 */
	private function loadModuleByName($name)
	{
		// Prepare PSR-0 compliant name
		$moduleNamespace = sprintf('%s\%s', $name, self::MODULE_CONFIG_FILE);

		// Ensure a module exists
		if (!class_exists($moduleNamespace)) {
			throw new RuntimeException(sprintf(
				'A %s module was not registered or its missing its base definition', $moduleNamespace
			));
		}

		$pathProvider = new PathProvider($this->appConfig->getModulesDir(), $name);

		$sl = new ServiceLocator();
		$sl->registerArray($this->services);

		// Build module instance
		$module = new $moduleNamespace($this, $sl, $this->appConfig, $pathProvider);

		// Routes must be global, so we'd extract them
		if (method_exists($module, 'getRoutes')) {
			$this->appendRoutes($this->prepareRoutes($moduleNamespace, $module->getRoutes()));
		}

		// Translations are optional
		if (method_exists($module, 'getTranslations')) {
			$translations = $module->getTranslations($this->appConfig->getLanguage());

			// Only array must be provided, otherwise ignore another type
			if (is_array($translations)) {
				$this->appendTranslations($translations);
			}
		}

		$this->loaded[$name] = $module;
		return $module;
	}

	/**
	 * Append translations
	 * 
	 * @param array $translations
	 * @return void
	 */
	private function appendTranslations(array $translations)
	{
		foreach ($translations as $string => $translation) {
			$this->translations[$string] = $translation;
		}
	}

	/**
	 * Append routes
	 * 
	 * @param array $routes
	 * @return void
	 */
	public function appendRoutes(array $routes)
	{
		foreach ($routes as $uri => $array) {
			$this->routes[$uri] = $array;
		}
	}

	/**
	 * Return routes
	 * 
	 * @return array
	 */
	public function getRoutes()
	{
		return $this->routes;
	}

	/**
	 * Returns translations
	 * 
	 * @return array
	 */
	public function getTranslations()
	{
		return $this->translations;
	}	
}

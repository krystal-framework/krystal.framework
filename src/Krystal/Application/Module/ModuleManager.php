<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
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
     * @return \Krystal\Application\Module\AbstractModule
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
     * @param string $name Module name to be checked
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
     * Returns a collection of loaded module instances
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
     * @return \Krystal\Application\Module\AbstractModule|boolean
     */
    private function loadModuleByName($name)
    {
        // Prepare PSR-0 compliant name
        $moduleNamespace = sprintf('%s\%s', $name, self::MODULE_CONFIG_FILE);

        // Ensure a module exists
        if (!class_exists($moduleNamespace)) {
            return false;
        }

        $pathProvider = new PathProvider($this->appConfig->getModulesDir(), $name);

        $sl = new ServiceLocator();
        $sl->registerArray($this->services);

        // Build module instance
        $module = new $moduleNamespace($this, $sl, $this->appConfig, $pathProvider, $name);

        // Routes must be global, so we'd extract them
        if (method_exists($module, 'getRoutes')) {
            $this->appendRoutes($this->prepareRoutes($moduleNamespace, $module->getRoutes()));
        }

        $this->loaded[$name] = $module;
        return $module;
    }

    /**
     * Clears loaded translations
     * 
     * @return void
     */
    public function clearTranslations()
    {
        $this->translations = array();
    }

    /**
     * Loads module translations
     * 
     * @param string $language
     * @return void
     */
    public function loadAllTranslations($language)
    {
        foreach ($this->loaded as $module) {
            $this->loadModuleTranslation($module, $language);
        }
    }

    /**
     * Loads translation message for particular module
     * 
     * @param \Krystal\Application\Module\AbstractModule $module Module instance
     * @param string $language Language name to be loaded
     * @return boolean
     */
    private function loadModuleTranslation(AbstractModule $module, $language)
    {
        // Translations are optional
        if (method_exists($module, 'getTranslations')) {
            $translations = $module->getTranslations($language);

            // Only array must be provided, otherwise ignore another type
            if (is_array($translations)) {
                // If that's an array, then append translations
                foreach ($translations as $string => $translation) {
                    $this->translations[$string] = $translation;
                }

                // And indicate success
                return true;
            }
        }

        // Failure by default
        return false;
    }

    /**
     * Append routes to the global stack
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
     * Returns all routes
     * 
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Returns all merged translations
     * 
     * @return array
     */
    public function getTranslations()
    {
        return $this->translations;
    }
}

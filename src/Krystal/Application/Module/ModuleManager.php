<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Module;

use Krystal\Application\AppConfigInterface;
use Krystal\Application\Module\Loader\LoaderInterface;
use Krystal\InstanceManager\ServiceLocator;
use Krystal\Application\AppConfig;
use Krystal\Filesystem\FileManager;
use RuntimeException;
use LogicException;

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
     * A collection of module names that must be considered as core ones
     * 
     * @var array
     */
    private $coreModules = array();

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

            // Validate on demand
            $this->validateCoreModuleNames();
        }
    }

    /**
     * Defines a collection of core modules
     * 
     * @param array $coreModules
     * @return void
     */
    public function setCoreModuleNames(array $coreModules)
    {
        $this->coreModules = $coreModules;
    }

    /**
     * Checks whether module name belongs to core collection
     * 
     * @param string $module
     * @throws \InvalidArgumentException If $module isn't a string
     * @return boolean
     */
    public function isCoreModule($module)
    {
        return $this->getCoreBag()->isCoreModule($module);
    }

    /**
     * Checks whether all collection consists of core modules
     * 
     * @param string $modules A collection of module names
     * @throws \InvalidArgumentException If $module isn't a string
     * @return boolean
     */
    public function isCoreModules(array $modules)
    {
        return $this->getCoreBag()->isCoreModules($module);
    }

    /**
     * Returns core bag instance
     * 
     * @return \Krystal\Application\Module\CoreBag
     */
    private function getCoreBag()
    {
        static $coreBag = null;

        if (is_null($coreBag)) {
            $coreBag = new CoreBag($this->getLoadedModuleNames(), $this->coreModules);
        }

        return $coreBag;
    }

    /**
     * Validates core modules on demand
     * 
     * @throws \LogicException On validation failure
     * @return void
     */
    private function validateCoreModuleNames()
    {
        if (!empty($this->coreModules)) {
            $coreBag = $this->getCoreBag();

            if (!$coreBag->hasAllCoreModules()) {
                throw new LogicException(sprintf(
                    'The framework can not start without defined core modules: %s', implode(', ', $coreBag->getMissingCoreModules())
                ));
            }
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
     * Returns a collection of unloaded modules
     * 
     * @param array $modules Target collection of required modules
     * @return array
     */
    public function getUnloadedModules(array $modules)
    {
        $unloaded = array();

        foreach ($modules as $module) {
            if (!$this->isLoaded($module)) {
                array_push($unloaded, $module);
            }
        }

        return $unloaded;
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
     * Validates module name against a pattern
     * 
     * @param string $name Module name
     * @return boolean
     */
    private function nameValid($name)
    {
        return ctype_alpha($name) && // Must contain only alphabetic characters
               ctype_upper(substr($name, 0, 1)); // The fist letter must be in uppercase
    }

    /**
     * Loads a module by its name
     * 
     * @param string $name Module name
     * @throws \LogicException If invalid module name is being processed
     * @return \Krystal\Application\Module\AbstractModule|boolean
     */
    private function loadModuleByName($name)
    {
        // First of all, make sure a valid module name is being processed
        if (!$this->nameValid($name)) {
            throw new LogicException(sprintf(
                'Invalid module name "%s" is being processed. Module name must start from a capital letter and contain only alphabetic characters', $name
            ));
        }

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
     * Shared method for performing module-related removals
     * 
     * @param string $path Target path
     * @return boolean Depending on success
     */
    private function performRemoval($path)
    {
        if (is_dir($path)) {
            return FileManager::rmdir($path);
        } else {
            return false;
        }
    }

    /**
     * Removes module data from cache directory
     * 
     * @param string $module
     * @return boolean
     */
    public function removeFromCacheDir($module)
    {
        // Create a path
        $path = $this->appConfig->getModuleCacheDir($module);
        return $this->performRemoval($path);
    }

    /**
     * Removes module data from uploading directory
     * 
     * @param string $module
     * @return boolean
     */
    public function removeFromUploadsDir($module)
    {
        // Create a path
        $path = $this->appConfig->getModuleUploadsDir($module);
        return $this->performRemoval($path);
    }

    /**
     * Removes a module from file system
     * 
     * @param string $module Module name (as in the folder)
     * @throws \LogicException If trying to remove core module
     * @return boolean Depending on success
     */
    public function removeFromFileSysem($module)
    {
        if ($this->isCoreModule($module)) {
            throw new LogicException(sprintf(
                'Trying to remove core module "%s". This is not allowed by design', $module
            ));
        }

        $path = sprintf('%s/%s', $this->appConfig->getModulesDir(), $module);
        return $this->performRemoval($path);
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
                // If that's an array, then append translations that correspond to a module
                foreach ($translations as $message => $translation) {
                    $this->translations[$module->getCurrentModuleName()][$message] = $translation;
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

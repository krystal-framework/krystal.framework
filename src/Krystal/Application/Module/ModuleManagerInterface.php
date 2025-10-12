<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Module;

interface ModuleManagerInterface
{
    /**
     * Initializes the module manager
     * 
     * @throws \RuntimeException If no modules found
     * @return void
     */
    public function initialize();

    /**
     * Defines a collection of core modules
     * 
     * @param array $coreModules
     * @return void
     */
    public function setCoreModuleNames(array $coreModules);

    /**
     * Checks whether module name belongs to core collection
     * 
     * @param string $module
     * @throws \InvalidArgumentException If $module isn't a string
     * @return boolean
     */
    public function isCoreModule($module);

    /**
     * Checks whether all collection consists of core modules
     * 
     * @param string $modules A collection of module names
     * @throws \InvalidArgumentException If $module isn't a string
     * @return boolean
     */
    public function isCoreModules(array $modules);

    /**
     * Returns module instance by its name
     * 
     * @param object $name
     * @return \Krystal\Application\Module\AbstractModule
     */
    public function getModule($name);

    /**
     * Checks whether module is loaded
     * 
     * @param string $name Module name to be checked
     * @return boolean
     */
    public function isLoaded($name);

    /**
     * Returns a collection of unloaded modules
     * 
     * @param array $modules Target collection of required modules
     * @return array
     */
    public function getUnloadedModules(array $modules);

    /**
     * Removes module data from cache directory
     * 
     * @param string $module
     * @return boolean
     */
    public function removeFromCacheDir($module);

    /**
     * Removes module data from uploading directory
     * 
     * @param string $module
     * @return boolean
     */
    public function removeFromUploadsDir($module);

    /**
     * Removes a module from file system
     * 
     * @param string $module Module name (as in the folder)
     * @throws \LogicException If trying to remove core module
     * @return boolean Depending on success
     */
    public function removeFromFileSysem($module);

    /**
     * Returns an array of loaded module names
     * 
     * @return array
     */
    public function getLoadedModuleNames();

    /**
     * Returns a collection of loaded module instances
     * 
     * @return array
     */
    public function getLoadedModules();

    /**
     * Clears loaded translations
     * 
     * @return void
     */
    public function clearTranslations();

    /**
     * Loads module translations
     * 
     * @param string $language
     * @return void
     */
    public function loadAllTranslations($language);

    /**
     * Append routes to the global stack
     * 
     * @param array $routes
     * @return void
     */
    public function appendRoutes(array $routes);

    /**
     * Returns all routes
     * 
     * @return array
     */
    public function getRoutes();

    /**
     * Returns all merged translations
     * 
     * @return array
     */
    public function getTranslations();
}

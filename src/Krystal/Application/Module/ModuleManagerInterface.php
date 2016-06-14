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
     * Removes module data from uploading directory
     * 
     * @param string $module
     * @throws \RuntimeException When trying to remove non-existent module from a directory
     * @return boolean
     */
    public function removeFromUploadsDir($module);

    /**
     * Removes a module from file system
     * 
     * @param string $module Module name (as in the folder)
     * @throws \RuntimeException When trying to remove non-existent module
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

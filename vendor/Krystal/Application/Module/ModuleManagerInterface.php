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

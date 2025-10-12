<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Module;

interface CoreBagInterface
{
    /**
     * Returns a collection of missing modules
     * 
     * @return array
     */
    public function getMissingCoreModules();

    /**
     * Checks whether all core modules are loaded
     * 
     * @return boolean
     */
    public function hasAllCoreModules();

    /**
     * Checks whether target module
     * 
     * @param string $module Module name
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
}

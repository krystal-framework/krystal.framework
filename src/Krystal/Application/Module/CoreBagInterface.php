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
}

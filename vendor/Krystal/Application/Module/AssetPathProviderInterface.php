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

interface AssetPathProviderInterface
{
    /**
     * Returns asset path
     * 
     * @param string $module Module's name
     * @return string
     */
    public function getPathByModule($module);

    /**
     * Returns a path with a module
     * 
     * @param string $module Module's name
     * @param string $path
     * @return string
     */
    public function getWithModulePath($module, $path);
}

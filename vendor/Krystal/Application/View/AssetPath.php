<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\View;

final class AssetPath implements AssetPathInterface
{
    const MODULE_PREFIX = '@';

    /**
     * An array of loaded module names
     * 
     * @var array
     */
    private $modules = array();

    /**
     * State initialization
     * 
     * @param array $modules
     * @return string
     */
    public function __construct(array $modules)
    {
        $this->modules = $modules;
    }

    /**
     * Returns asset path to a module
     * 
     * @param string $module
     * @return string
     */
    private function getAssetPathByModule($module)
    {
        return sprintf('/module/%s/Assets', $module);
    }

    /**
     * Replaces a module path inside provided path
     * 
     * @param string $path Target path
     * @return string
     */
    public function replace($path)
    {
        foreach ($this->modules as $module) {

            $target = self::MODULE_PREFIX.$module;

            if (strpos($path, $target) !== false) {
                $path = str_replace($target, $this->getAssetPathByModule($module), $path);
                break;
            }
        }

        return $path;
    }
}

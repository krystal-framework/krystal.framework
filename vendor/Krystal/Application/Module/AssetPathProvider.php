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

/* This provider is instantiated in controllers only */
final class AssetPathProvider implements AssetPathProviderInterface
{
    /**
     * Base URL
     * 
     * @var string
     */
    private $baseUrl;

    /**
     * Target module name
     *
     * @var string
     */
    private $moduleName;

    /**
     * Assets directory name
     * 
     * @var string
     */
    private $assetsDirName;

    /**
     * State initialization
     * 
     * @param string $baseUrl
     * @param string $assetsDirName
     * @return void
     */
    public function __construct($baseUrl = null, $assetsDirName = 'Assets')
    {
        $this->baseUrl = $baseUrl;
        $this->assetsDirName = $assetsDirName;
    }

    /**
     * Returns asset path
     * 
     * @param string $module Module's name
     * @return string
     */
    public function getPathByModule($module)
    {
        return sprintf('/module/%s/%s', $module, $this->assetsDirName);
    }

    /**
     * Returns a path with a module
     * 
     * @param string $module Module's name
     * @param string $path
     * @return string
     */
    public function getWithModulePath($module, $path)
    {
        return sprintf('%s/%s', $this->getPathByModule($module), $path);
    }
}

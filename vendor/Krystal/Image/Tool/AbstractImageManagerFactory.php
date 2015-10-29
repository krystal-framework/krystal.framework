<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Image\Tool;

use Krystal\Image\Tool\ImageManager;
use Krystal\Application\AppConfigInterface;

abstract class AbstractImageManagerFactory
{
    /**
     * Application configuration provider
     * 
     * @var \Krystal\Application\AppConfigInterface
     */
    protected $appConfig;

    /**
     * Configuration entity
     * 
     * @var \Krystal\Stdlib\VirtualBag
     */
    protected $config;

    /**
     * State initialization
     * 
     * @param \Krystal\Application\AppConfigInterface $appConfig
     * @param $config
     * @return void
     */
    public function __construct(AppConfigInterface $appConfig, $config = null)
    {
        $this->appConfig = $appConfig;
        $this->config = $config;
    }

    /**
     * Returns root directory, which is used to manage image files and also to build image paths
     * 
     * @return string
     */
    protected function getRootDir()
    {
        return $this->appConfig->getRootDir();
    }

    /**
     * Returns root URL, which is used to build paths for images
     * 
     * @return string
     */
    protected function getRootUrl()
    {
        return $this->appConfig->getRootUrl();
    }

    /**
     * Returns image manager's path
     * 
     * @return string
     */
    abstract protected function getPath();

    /**
     * Returns image manager's configuration
     * 
     * @return array
     */
    abstract protected function getConfig();

    /**
     * Builds image manager's instance
     * 
     * @return \Krystal\Image\Tool\ImageManager
     */
    final public function build()
    {
        return new ImageManager($this->getPath(), $this->getRootDir(), $this->getRootUrl(), $this->getConfig());
    }
}

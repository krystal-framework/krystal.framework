<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Component;

use Krystal\Application\AppConfig as Component;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;
use Krystal\Application\InputInterface;
use Krystal\Http\RequestInterface;
use RuntimeException;

final class AppConfig implements ComponentInterface
{
    /**
     * {@inheritDoc}
     */
    public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input)
    {
        if (!isset($config['components']['view']['theme'])) {
            throw new RuntimeException('You should provide a theme');
        }

        $request = $container->get('request');

        $appConfig = new Component();
        $appConfig->setRootDir(isset($config['root_dir']) ? $config['root_dir'] : $request->getRootDir())
                  ->setRootUrl(isset($config['root_url']) ? $config['root_url'] : $request->getBaseUrl())
                  ->setDataDir(isset($config['data_dir']) ? $config['data_dir'] : $appConfig->getRootDir() . '/data')
                  ->setUploadsDir(isset($config['uploads_dir']) ? $config['uploads_dir'] : $appConfig->getDataDir() . '/uploads')
                  ->setUploadsUrl(isset($config['uploads_url']) ? $config['uploads_dir'] : $appConfig->getRootUrl() . '/data/uploads')
                  ->setLanguage(!empty($config['components']['translator']['default']) ? $config['components']['translator']['default'] : null)
                  ->setTheme($config['components']['view']['theme'])
                  ->setThemesDir(isset($config['themes_dir']) ? $config['themes_dir'] : $appConfig->getRootDir(). '/themes')
                  ->setThemeDir($appConfig->getThemesDir().'/'.$appConfig->getTheme())
                  ->setTempDir(isset($config['temp_dir']) ? $config['temp_dir'] : $appConfig->getDataDir().'/tmp')
                  ->setCacheDir(isset($config['cache_dir']) ? $config['cache_dir'] : $appConfig->getDataDir().'/cache')
                  ->setModulesDir(isset($config['module_dir']) ? $config['module_dir'] : $appConfig->getRootDir() . '/module');

        return $appConfig;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'appConfig';
    }
}

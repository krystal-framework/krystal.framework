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

use Krystal\Application\Route\RouteNotation;
use Krystal\Application\Route\MapManager;
use Krystal\Application\Route\UrlBuilder as Component;
use Krystal\Application\InputInterface;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;

final class UrlBuilder implements ComponentInterface
{
    /**
     * {@inheritDoc}
     */
    public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input)
    {
        $moduleManager = $container->get('moduleManager');
        $mapManager = new MapManager($moduleManager->getRoutes(), new RouteNotation());

        return new Component($mapManager);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'urlBuilder';
    }
}

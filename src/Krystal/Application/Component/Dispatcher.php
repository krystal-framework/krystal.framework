<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Component;

use Krystal\Application\FrontController\Dispatcher as Component;
use Krystal\Application\FrontController\ControllerFactory;
use Krystal\Application\Route\RouteNotation;
use Krystal\Application\Route\MapManager;
use Krystal\Application\InputInterface;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;
use Krystal\InstanceManager\ServiceLocator;

final class Dispatcher implements ComponentInterface
{
    /**
     * {@inheritDoc}
     */
    public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input)
    {
        $moduleManager = $container->get('moduleManager');

        $mapManager = new MapManager($moduleManager->getRoutes(), new RouteNotation());
        $dispatcher = new Component($mapManager);

        // This is critical
        $services = array_merge(array($this->getName() => $dispatcher), $container->getAll());

        $controllerFactory = new ControllerFactory(new ServiceLocator($services));
        $dispatcher->setControllerFactory($controllerFactory);

        return $dispatcher;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'dispatcher';
    }
}

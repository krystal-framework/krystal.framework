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

use Krystal\Profiler\Profiler as Component;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;
use Krystal\Application\InputInterface;

final class Profiler implements ComponentInterface
{
    /**
     * {@inheritDoc}
     */
    public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input)
    {
        $profiler = new Component();

        $view = $container->get('view');
        $view->addVariable('profiler', $profiler);

        return $profiler;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'profiler';
    }
}

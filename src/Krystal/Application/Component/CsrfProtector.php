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

use Krystal\Application\InputInterface;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;
use Krystal\Security\CsrfProtector as Component;

final class CsrfProtector implements ComponentInterface
{
    /**
     * {@inheritDoc}
     */
    public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input)
    {
        $sessionBag = $container->get('sessionBag');
        $view = $container->get('view');

        $component = new Component($sessionBag);
        $component->prepare();

        // Append global $csrfToken variable to all templates
        $view->addVariable('csrfToken', $component->getToken());

        return $component;
    }

    /**
     * {@inheritoDoc}
     */
    public function getName()
    {
        return 'csrfProtector';
    }
}

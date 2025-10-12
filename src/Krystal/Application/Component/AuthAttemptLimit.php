<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Component;

use Krystal\InstanceManager\DependencyInjectionContainerInterface;
use Krystal\Authentication\Protection\AttemptLimit;
use Krystal\Application\InputInterface;

final class AuthAttemptLimit implements ComponentInterface
{
    /**
     * {@inheritDoc}
     */
    public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input)
    {
        $sessionBag = $container->get('sessionBag');
        return new AttemptLimit($sessionBag);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'authAttemptLimit';
    }
}

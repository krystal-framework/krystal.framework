<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Component;

use Krystal\Authentication\AuthManager as Component;
use Krystal\Authentication\Cookie\ReAuth;
use Krystal\Authentication\HashProvider;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;
use Krystal\Application\InputInterface;

final class AuthManager implements ComponentInterface
{
    /**
     * {@inheritDoc}
     */
    public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input)
    {
        $cookieBag = $container->get('request')->getCookieBag();
        $sessionBag = $container->get('sessionBag');

        $hashProvider = new HashProvider();
        $reAuth = new ReAuth($cookieBag, $hashProvider);

        return new Component($sessionBag, $reAuth, $hashProvider);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'authManager';
    }
}

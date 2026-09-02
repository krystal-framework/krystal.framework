<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Component;

use Krystal\Authentication\AuthManager as Component;
use Krystal\Authentication\Cookie\RememberMeManager;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;
use Krystal\Application\InputInterface;
use InvalidArgumentException;

final class AuthManager implements ComponentInterface
{
    /**
     * {@inheritDoc}
     */
    public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input)
    {
        if (empty($config['components']['auth_manager']['secret_key'])) {
            throw new InvalidArgumentException('Missing required "secret_key" parameter in authManager configuration');
        }

        $cookieBag = $container->get('request')->getCookieBag();
        $sessionBag = $container->get('sessionBag');
        $rememberMe = new RememberMeManager($cookieBag, $config['components']['auth_manager']['secret_key']);

        return new Component($sessionBag, $rememberMe);
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'authManager';
    }
}

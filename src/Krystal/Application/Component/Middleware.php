<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Component;

use Krystal\Application\InputInterface;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;
use Krystal\Application\Middleware\MiddlewareRunner;

final class Middleware implements ComponentInterface
{
    /**
     * {@inheritDoc}
     */
    public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input)
    {
        return new MiddlewareRunner();
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'middleware';
    }
}
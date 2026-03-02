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
use Krystal\Logging\LoggerFactory;

final class Logger implements ComponentInterface
{
    /**
     * {@inheritDoc}
     */
    public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input)
    {
        if (isset($config['components']['logger']['writers'])) {
            return LoggerFactory::build($config['components']['logger']);
        } else {
            // No configuration provided
            return false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'logger';
    }
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Component;

use Krystal\InstanceManager\DependencyInjectionContainerInterface;
use Krystal\Application\InputInterface;

interface ComponentInterface
{
    /**
     * Returns prepared and configured component's instance
     * 
     * @param \Krystal\InstanceManager\DependencyInjectionContainerInterface $container
     * @param array $config
     * @param \Krystal\Application\InputInterface $input
     * @return object
     */
    public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input);

    /**
     * Returns component's name
     * 
     * @return string
     */
    public function getName();
}

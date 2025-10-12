<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Component;

use Krystal\Http\Response\HttpResponse as Component;
use Krystal\Http\HeaderBag;
use Krystal\Application\InputInterface;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;

final class Response implements ComponentInterface
{
    /**
     * {@inheritDoc}
     */
    public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input)
    {
        return new Component(new HeaderBag(), $input->getServer());
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'response';
    }
}

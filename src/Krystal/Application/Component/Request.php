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

use Krystal\Http\Request as Component;
use Krystal\Http\CookieBag;
use Krystal\Application\InputInterface;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;

final class Request implements ComponentInterface
{
    /**
     * {@inheritDoc}
     */
    public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input)
    {
        $cookieBag = new CookieBag($input->getCookie());
        return new Component($input->getQuery(), $input->getPost(), $cookieBag, $input->getServer(), $input->getFiles());
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'request';
    }
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Component;

use Krystal\Session\SessionBag as Component;
use Krystal\Application\InputInterface;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;

final class SessionBag implements ComponentInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input)
	{
		$cookieBag = $container->get('request')->getCookieBag();

		$sessionBag = new Component($cookieBag);
		//@TODO Inject cookie options
		$sessionBag->start();

		return $sessionBag;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'sessionBag';
	}
}

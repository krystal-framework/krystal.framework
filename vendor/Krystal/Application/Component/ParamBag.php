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

use Krystal\ParamBag\ParamBag as Component;
use Krystal\Application\InputInterface;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;

final class ParamBag implements ComponentInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input)
	{
		$params = array();

		if (isset($config['components']['paramBag'])) {
			$params = $config['components']['paramBag'];
		}

		return new Component($params);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'paramBag';
	}
}

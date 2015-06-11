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

use Krystal\Db\Sql\MapperFactory as Component;
use Krystal\Application\InputInterface;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;

final class MapperFactory implements ComponentInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input)
	{
		$connections = $container->get('db');

		// If no connections, then stop
		if (empty($connections)) {
			return;
		}

		$queryBuilder = $connections['mysql'];

		$factory = new Component($queryBuilder);
		$factory->setPaginator($container->get('paginator'));

		return $factory;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'mapperFactory';
	}
}

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
use RuntimeException;

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

		if (!isset($config['components']['mapperFactory']['connection'])) {
			throw new RuntimeException('Missing connection to use for building mappers');
		}

		// Defined connection name in configuration. Grab it
		$current = $config['components']['mapperFactory']['connection'];

		if (!in_array($current, array_keys($connections))) {
			throw new RuntimeException(sprintf(
				'The connection for the mapper factory "%s" was not defined in database configuration section', $current
			));
		} else {
			$db = $connections[$current];
		}

		return new Component($db, $container->get('paginator'));
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'mapperFactory';
	}
}

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

use Krystal\Application\InputInterface;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;
use Krystal\Config\Sql\SqlConfigServiceFactory;
use RuntimeException;
use LogicException;

final class Config implements ComponentInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input)
	{
		// Start working only in case a section is defined
		if (isset($config['components']['config'])) {
			// Just a short reference
			$config = $config['components']['config'];

			if (isset($config['adapter'])) {
				switch ($config['adapter']) {

					// Cross SQL adapter
					case 'sql':

						// Make sure the database component is available, before processing the rest
						if (!$container->exists('db')) {
							throw new LogicException('Can not use SQL adapter without configured database connection');
						}

						// Grab available database connection
						$db = $container->get('db');

						if (isset($config['options']['connection']) && isset($config['options']['table'])) {
							// Grab connection's name
							$connection = $db[$config['options']['connection']];
						} else {
							throw new LogicException('No connection or table name defined for configuration service');
						}

						return SqlConfigServiceFactory::build($connection->getPdo(), $config['options']['table']);

					default:
						throw new RuntimeException(sprintf('Unsupported configuration adapter supplied "%s"', $config['adapter']));
				}

			} else {
				throw new RuntimeException('No adapter defined for configuration service');
			}

		} else {

			// No service definition found in configuration array, so return false
			return false;
		}
	}

	/**
	 * {@inheritoDoc}
	 */
	public function getName()
	{
		return 'config';
	}
}

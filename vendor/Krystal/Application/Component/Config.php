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

final class Config implements ComponentInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input)
	{
		$db = $container->get('db');
		$connection = $db['mysql'];

		$pdo = $connection->getPdo();

		$config = SqlConfigServiceFactory::build($pdo, 'config');

		return $config;
	}

	/**
	 * {@inheritoDoc}
	 */
	public function getName()
	{
		return 'config';
	}
}

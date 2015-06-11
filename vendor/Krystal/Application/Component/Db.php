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

use Krystal\Db\Sql\Connector;
use Krystal\Db\Sql\QueryLogger;
use Krystal\Db\Sql\QueryBuilder;
use Krystal\Db\Sql\Db as Component;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;
use Krystal\Application\InputInterface;

final class Db implements ComponentInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input)
	{
		if (isset($config['components']['db'])) {
			$db = $config['components']['db'];
			
			// Prepared connection instances
			$instances = array();
			
			foreach ($db as $name => $data) {
				$connection = $data['connection'];
				$events = $data['events'];
				
				$paginator = $container->get('paginator');
				
				switch ($name) {
					case 'mysql':
						try {
							
							$pdo = new Connector\MySQL($connection);
							$queryBuilder = new QueryBuilder();
							
							$instances[$name] = new Component($queryBuilder, $pdo, $paginator, new QueryLogger());
							
						} catch(\PDOException $e) {
							
							if (isset($events['fail']) && is_callable($events['fail'])) {
								call_user_func($events['fail'], $e);
							}
						}
					
					break;
				}
			}
			
			return $instances;
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'db';
	}
}

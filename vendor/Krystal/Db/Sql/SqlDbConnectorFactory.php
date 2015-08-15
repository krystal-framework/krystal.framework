<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Sql;

use Krystal\Db\InvalidDatabaseConfigurationException;
use Krystal\Db\Sql\QueryLogger;
use Krystal\Db\Sql\QueryBuilder;
use Krystal\Db\Sql\Db;
use Krystal\Paginate\PaginatorInterface;
use PDOException;
use RuntimeException;

final class SqlDbConnectorFactory implements SqlDbConnectorFactoryInterface
{
	/**
	 * Pagination service
	 * 
	 * @var \Krystal\Paginate\PaginatorInterface
	 */
	private $paginator;

	/**
	 * A map for vendor and class names
	 * 
	 * @var array
	 */
	private $map = array(
		'mysql' => '\Krystal\Db\Sql\Connector\MySQL',
		'postgresql' => '\Krystal\Db\Sql\Connector\PostgreSQL',
		'sqlite' => '\Krystal\Db\Sql\Connector\SQLite'
	);

	/**
	 * State initialization
	 * 
	 * @param \Krystal\Paginate\PaginatorInterface $paginator Paginator instance
	 * @return void
	 */
	public function __construct(PaginatorInterface $paginator)
	{
		$this->paginator = $paginator;
	}

	/**
	 * Builds database service instance
	 * 
	 * @param string $vendor Database vendor name
	 * @param array $options Options for connection, such as username and password
	 * @throws \RuntimeException If unknown vendor name supplied
	 * @return \Krystal\Db\Sql\DbInterface
	 */
	public function build($vendor, array $options)
	{
		// First of all, make sure it's possible to instantiate PDO instance by vendor name
		if (isset($this->map[$vendor])) {
			$class = $this->map[$vendor];
		} else {
			throw new RuntimeException(sprintf('Unknown database vendor name supplied "%s"', $vendor));
		}

		try {
			$pdo = new $class($options);
		} catch (PDOException $e) {
			throw new InvalidDatabaseConfigurationException(sprintf('Invalid database configuration. The driver reported "%s"', $e->getMessage()));
		}

		return new Db(new QueryBuilder(), $pdo, $this->paginator, new QueryLogger());
	}
}

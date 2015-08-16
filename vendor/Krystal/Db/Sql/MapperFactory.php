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

use Krystal\InstanceManager\InstanceBuilder;
use Krystal\Paginate\PaginatorInterface;
use Krystal\Db\MapperFactoryInterface;

final class MapperFactory implements MapperFactoryInterface
{
	/**
	 * Database service
	 * 
	 * @var \Krystal\Db\Sql\Db
	 */
	private $db;

	/**
	 * Optional pagination service
	 * 
	 * @var \Krystal\Paginate\PaginatorInterface
	 */
	private $paginator;

	/**
	 * State initialization
	 * 
	 * @param \Krystal\Db\Sql\DbInterface $db Database service
	 * @param \Krystal\Paginate\PaginatorInterface $paginator
	 * @return void
	 */
	public function __construct(DbInterface $db, PaginatorInterface $paginator = null)
	{
		$this->db = $db;

		if (!is_null($paginator)) {
			$this->paginator = $paginator;
		}
	}

	/**
	 * Builds a mapper
	 * 
	 * @param string $namespace PSR-0 compliant class namespace
	 * @return \Krystal\Db\Sql\AbstractMapper
	 */
	public function build($namespace)
	{
		$args = array($this->db);

		if (is_object($this->paginator)) {
			array_push($args, $this->paginator);
		}

		$builder = new InstanceBuilder();
		return $builder->build($namespace, $args);
	}
}

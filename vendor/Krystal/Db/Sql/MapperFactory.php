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

use Krystal\InstanceManager\ObjectContainer;
use Krystal\Paginate\PaginatorInterface;

final class MapperFactory
{
	/**
	 * Database handler
	 * 
	 * @var \Krystal\Db\Sql\Db
	 */
	private $db;

	/**
	 * Data paginator
	 * 
	 * @var \Krystal\Paginate\PaginatorInterface
	 */
	private $paginator;

	/**
	 * @var array
	 */
	private $cache = array();

	/**
	 * State initialization
	 * 
	 * @param \Krystal\Db\Sql\Db $db
	 * @param \Krystal\Paginate\PaginatorInterface $paginator
	 * @return void
	 */
	public function __construct(Db $db, PaginatorInterface $paginator = null)
	{
		$this->db = $db;

		if (!is_null($paginator)) {
			$this->setPaginator($paginator);
		}
	}

	/**
	 * Defines paginator's instance
	 * 
	 * @param \Krystal\Paginate\PaginatorInterface $paginator
	 * @return void
	 */
	public function setPaginator(PaginatorInterface $paginator)
	{
		$this->paginator = $paginator;
	}

	/**
	 * Builds an instance
	 * 
	 * @param string $namespace PSR-0 compliant mapper
	 * @return void
	 */
	public function build($namespace)
	{
		$namespace = rtrim($namespace, '\\');
		$namespace = str_replace('/', '\\', $namespace);

		// Attempt to autoload a namespace
		if (class_exists($namespace)) {
			if (!array_key_exists($namespace, $this->cache)) {
				
				$instance = new $namespace($this->db, $this->paginator);
				$this->cache[$namespace] = $instance;
				
				return $instance;
				
			} else {
				return $this->cache[$namespace];
			}
			
		} else {
			trigger_error(sprintf('Attempted to read non-existing class "%s"', $namespace));
		}
	}
}

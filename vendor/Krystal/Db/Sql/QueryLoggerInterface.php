<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Sql;

interface QueryLoggerInterface
{
	/**
	 * Adds a query to the stack
	 * 
	 * @param string $query
	 * @return void
	 */
	public function add($query);

	/**
	 * Returns all queries
	 * 
	 * @return array
	 */
	public function getAll();

	/**
	 * Counts amount of queries in the stack
	 * 
	 * @return integer
	 */
	public function getCount();
}

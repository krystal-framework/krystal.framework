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

interface DbInterface extends QueryBuilderInterface
{
	/**
	 * Automatically paginates result-set
	 * 
	 * @param integer $page
	 * @param integer $itemsPerPage
	 * @param string $column Column to be selected when counting
	 * @return \Krystal\Db\QueryBuilder
	 */
	public function paginate($page, $itemsPerPage, $column = '1');

	/**
	 * Returns PDO instance
	 * 
	 * @return \PDO
	 */
	public function getPdo();

	/**
	 * Queries for all result-set
	 * 
	 * @param string $column Optionally can be filtered by a column
	 * @return array
	 */
	public function queryAll($column = null);

	/**
	 * Queries for a single result-set
	 * 
	 * @param string $column
	 * @return array|string
	 */
	public function query($column = null);

	/**
	 * Executes a command
	 * 
	 * @return boolean
	 */
	public function execute();
}

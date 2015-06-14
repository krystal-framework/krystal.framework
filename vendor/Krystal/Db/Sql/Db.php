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

use PDO;
use Krystal\Paginate\PaginatorInterface;

/* This is just a bridge between PDO and QueryBuilder, that makes it all work */
final class Db implements DbInterface
{
	/**
	 * Query builder
	 * 
	 * @var \Krystal\Db\Sql\QueryBuilderInterface
	 */
	private $queryBuilder;

	/**
	 * Built-in PDO instance
	 * 
	 * @var \PDO
	 */
	private $pdo;

	/**
	 * Prepared paginator's instance
	 * 
	 * @var \Krystal\Paginate\PaginatorInterface
	 */
	private $paginator;

	/**
	 * Query logger
	 * 
	 * @var \Krystal\Db\Sql\QueryLoggerInterface
	 */
	private $queryLogger;

	/**
	 * PDO's bindings
	 * 
	 * @var array
	 */
	private $bindings = array();

	/**
	 * State initialization
	 * 
	 * @param \Krystal\Db\Sql\QueryBuilderInterface $queryBuilder
	 * @param \PDO $pdo
	 * @param \Krystal\Paginate\PaginatorInterface $paginator
	 * @param \Krystal\Db\Sql\QueryLoggerInterface $queryLogger
	 * @return void
	 */
	public function __construct(QueryBuilderInterface $queryBuilder, PDO $pdo, PaginatorInterface $paginator, QueryLoggerInterface $queryLogger)
	{
		$this->queryBuilder = $queryBuilder;
		$this->pdo = $pdo;
		$this->paginator = $paginator;
		$this->queryLogger = $queryLogger;
	}

	/**
	 * Purely for quick debugging in mappers
	 * 
	 * @return string
	 */
	public function __toString()
	{
		return $this->queryBuilder->getQueryString();
	}

	/**
	 * Returns query builder
	 * 
	 * @return \Krystal\Db\QueryBuilder
	 */
	public function getQueryBuilder()
	{
		return $this->queryBuilder;
	}

	/**
	 * Returns PDO instance
	 * 
	 * @return \PDO
	 */
	public function getPdo()
	{
		return $this->pdo;
	}

	/**
	 * Returns query logger
	 * 
	 * @return \Krystal\Db\Sql\QueryLogger
	 */
	public function getQueryLogger()
	{
		return $this->queryLogger;
	}

	/**
	 * 
	 * @param array $data
	 * @return array
	 */
	private function asData(array $data)
	{
		foreach ($data as $key => $value) {
			$placeholder = $this->toPlaceholder($key);

			$data[$key] = $placeholder;
			$this->bind($placeholder, $value);
		}

		return $data;
	}

	/**
	 * Returns count for pagination
	 * 
	 * @return integer
	 */
	private function getCount()
	{
		// Save initial state
		$original = clone $this->queryBuilder;
		$bindings = $this->bindings;

		// Set guessed query and execute it
		$this->queryBuilder->setQueryString($this->queryBuilder->guessCountQuery());
		$count = $this->query('count');

		// And finally restore initial state
		$this->queryBuilder = $original;
		$this->bindings = $bindings;

		return $count;
	}

	/**
	 * Logs a query
	 * 
	 * @return void
	 */
	private function log()
	{
		$this->queryLogger->add($this->queryBuilder->getQueryString());
	}

	/**
	 * Converts column name to its placeholder
	 * 
	 * @param string $column
	 * @return string
	 */
	private function toPlaceholder($column)
	{
		return ':'.$column;
	}

	/**
	 * Automatically paginates result
	 * 
	 * @param integer $page
	 * @param integer $itemsPerPage
	 * @return \Krystal\Db\QueryBuilder
	 */
	public function paginate($page, $itemsPerPage)
	{
		$count = $this->getCount();

		// Alter paginator's state
		$this->paginator->tweak($count, $itemsPerPage, $page);
		$this->limit($this->paginator->countOffset(), $this->paginator->getItemsPerPage());

		return $this;
	}

	/**
	 * Binds a value
	 * 
	 * @param string $placeholder
	 * @oaram string $value
	 * @return void
	 */
	private function bind($placeholder, $value)
	{
		$this->bindings[$placeholder] = $value;
	}

	/**
	 * Clears the stack
	 * 
	 * @return void
	 */
	private function clear()
	{
		$this->queryBuilder->clear();
		$this->bindings = array();
	}

	/**
	 * Executes raw query
	 * 
	 * @param string $query
	 * @param array $bindings
	 * @return \Krystal\Db\Sql
	 */
	public function raw($query, array $bindings = array())
	{
		if (!empty($bindings)) {
			foreach ($bindings as $column => $value) {
				$this->bind($this->toPlaceholder($column), $value);
			}
		}

		$this->queryBuilder->raw($query);
		return $this;
	}

	/**
	 * Queries for all result-set
	 * 
	 * @param string $column Optionally can be filtered by a column
	 * @return array
	 */
	public function queryAll($column = null)
	{
		$stmt = $this->pdo->prepare($this->queryBuilder->getQueryString());
		$stmt->execute($this->bindings);

		$this->log();
		$this->clear();

		$resultset = $stmt->fetchAll();

		if ($column == null) {
			return $resultset;
		} else {
			$result = array();

			foreach ($resultset as $row) {
				$result[] = $row[$column];
			}

			return $result;
		}
	}

	/**
	 * Queries for a single result-set
	 * 
	 * @param string $column Optionally can be filtered by a column
	 * @return array|string
	 */
	public function query($column = null)
	{
		$stmt = $this->pdo->prepare($this->queryBuilder->getQueryString());
		$stmt->execute($this->bindings);

		$this->log();
		$this->clear();

		$result = $stmt->fetch();

		if ($column !== null) {
			return $result[$column];
		} else {
			return $result;
		}
	}

	/**
	 * Executes a command
	 * 
	 * @return boolean
	 */
	public function execute()
	{
		$stmt = $this->pdo->prepare($this->queryBuilder->getQueryString());
		$stmt->execute($this->bindings);

		$this->log();
		$this->clear();

		return true;
	}

	/**
	 * Appends RAND() function
	 * 
	 * @return \Krystal\Db\Sql\Db
	 */
	public function rand()
	{
		$this->queryBuilder->rand();
		return $this;
	}
	
	/**
	 * Appends DESC
	 * 
	 * @return \Krystal\Db\Sql
	 */
	public function desc()
	{
		$this->queryBuilder->desc();
		return $this;
	}

	/**
	 * Appends sorting clause
	 * 
	 * @param string $type
	 * @return \Krystal\Db\Sql\Db
	 */
	public function sort($type)
	{
		$this->queryBuilder->sort($type);
		return $this;
	}

	/**
	 * Appends SELECT expression
	 * 
	 * @param mixed $target A string or an array of columns to be selected
	 * @param boolean $distinct Whether to append DISTINCT right after SELECT statement
	 * @return \Krystal\Db\Sql\Db
	 */
	public function select($target = null, $distinct = false)
	{
		$this->queryBuilder->select($target, $distinct);
		return $this;
	}

	/**
	 * Appends COUNT() expression
	 * 
	 * @param string $target
	 * @param string $alias
	 * @return \Krystal\Db\Sql\Db
	 */
	public function count($target, $alias = null)
	{
		$this->queryBuilder->count($target, $alias);
		return $this;
	}

	/**
	 * Appends ORDER BY
	 * 
	 * @param string $type
	 * @return \Krystal\Db\Sql\Db
	 */
	public function orderBy($type)
	{
		$this->queryBuilder->orderBy($type);
		return $this;
	}
	
	/**
	 * Appends MAX() aggregate function
	 * 
	 * @param string $target
	 * @param string $alias
	 * @return \Krystal\Db\Sql\Db
	 */
	public function max($target, $alias = null)
	{
		$this->queryBuilder->max($target, $alias);
		return $this;
	}

	/**
	 * Appends DELETE expression
	 * 
	 * @return \Krystal\Db\Sql\Db
	 */
	public function delete()
	{
		$this->queryBuilder->delete();
		return $this;
	}

	/**
	 * Appends FROM expression
	 * 
	 * @param string $table
	 * @return \Krystal\Db\Sql\Db
	 */
	public function from($table)
	{
		$this->queryBuilder->from($table);
		return $this;
	}

	/**
	 * Appends limit
	 * 
	 * @param integer $offset
	 * @param integer|null $amount
	 * @return \Krystal\Db\Sql\Db
	 */
	public function limit($offset, $amount = null)
	{
		$this->queryBuilder->limit($offset, $amount);
		return $this;
	}

	/**
	 * Updates a table
	 * 
	 * @param string $table
	 * @param array $data
	 * @return \Krystal\Db\Sql\Db
	 */
	public function update($table, array $data)
	{
		$this->queryBuilder->update($table, $this->asData($data));
		return $this;
	}

	/**
	 * Inserts data
	 * 
	 * @param string $table
	 * @param array $data Data to be inserted
	 * @param boolean $ignore
	 * @return \Krystal\Db\Sql\Db
	 */
	public function insert($table, array $data, $ignore = false)
	{
		$this->queryBuilder->insert($table, $this->asData($data), $ignore);
		return $this;
	}

	/**
	 * Adds a constraint to q query
	 * 
	 * @param string $method
	 * @param string $column
	 * @param string $operator
	 * @param string $value
	 * @param boolean $filter Whether to filter by empty value
	 */
	private function constraint($method, $column, $operator, $value, $filter)
	{
		if ($filter == true && empty($value)) {
			return $this;
		}

		$placeholder = $this->toPlaceholder($column);

		call_user_func(array($this->queryBuilder, $method), $column, $operator, $placeholder);
		$this->bind($placeholder, $value);

		return $this;
	}

	/**
	 * Adds where clause
	 * 
	 * @param string $column
	 * @param string $operator
	 * @param string $value
	 * @param boolean $filter Whether to filter by empty value
	 * @return \Krystal\Db\Sql\Db
	 */
	public function where($column, $operator, $value, $filter = false)
	{
		return $this->constraint(__FUNCTION__, $column, $operator, $value, $filter);
	}

	/**
	 * Appends OR for WHERE clause
	 * 
	 * @param string $column
	 * @param string $operator
	 * @param string $value
	 * @param boolean $filter Whether to filter by empty value
	 * @return \Krystal\Db\Sql\Db
	 */
	public function orWhere($column, $operator, $value, $filter = false)
	{
		return $this->constraint(__FUNCTION__, $column, $operator, $value, $filter);
	}

	/**
	 * Appends AND for WHERE clause
	 * 
	 * @param string $column
	 * @param string $operator
	 * @param boolean $filter Whether to filter by empty value
	 * @return \Krystal\Db\Sql\Db
	 */
	public function andWhere($column, $operator, $value, $filter = false)
	{
		return $this->constraint(__FUNCTION__, $column, $operator, $value, $filter);
	}

	/**
	 * Appends where clause with equality operator
	 * 
	 * @param string $column
	 * @param string $value
	 * @param boolean $filter Whether to filter by empty value
	 * @return \Krystal\Db\Sql\Db
	 */
	public function whereEquals($column, $value, $filter = false)
	{
		return $this->where($column, '=', $value, $filter);
	}
	
	
	public function whereLike($column, $value, $filter = false)
	{
		return $this->where($column, 'LIKE', $value, $filter);
	}
	
	/**
	 * Appends AND for where clause with equality operator
	 * 
	 * @param string $column
	 * @param string $value
	 * @param boolean $filter Whether to filter by empty value
	 * @return \Krystal\Db\Sql\Db
	 */
	public function andWhereEquals($column, $value, $filter = false)
	{
		return $this->andWhere($column, '=', $value, $filter);
	}

	/**
	 * Appends WHERE clause with != operator
	 * 
	 * @param string $column
	 * @param string $value
	 * @param boolean $filter Whether to filter by empty value
	 * @return \Krystal\Db\Sql\Db
	 */
	public function andWhereNotEquals($column, $value, $filter = false)
	{
		return $this->andWhere($column, '!=', $value, $filter);
	}

	/**
	 * Appends truncate statement
	 * 
	 * @param string $table Table name to be truncated
	 * @return \Krystal\Db\Sql\Db
	 */
	public function truncate($table)
	{
		$this->queryBuilder->truncate($table);
		return $this;
	}
}

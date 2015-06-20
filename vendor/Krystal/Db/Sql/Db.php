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
	 * Appends increment condition
	 * 
	 * @param string $table
	 * @param string $column
	 * @param integer $step
	 * @return \Krystal\Db\Sql\Db
	 */
	public function increment($table, $column, $step = 1)
	{
	}

	/**
	 * Appends decrement condition
	 * 
	 * @param string $table
	 * @param string $column
	 * @param integer $step
	 * @return \Krystal\Db\Sql\Db
	 */
	public function decrement($table, $column, $step = 1)
	{
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
	public function orderBy($type = null)
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
	public function max($column, $alias = null)
	{
		$this->queryBuilder->max($column, $alias);
		return $this;
	}

	/**
	 * Appends MIN() aggregate function
	 * 
	 * @param string $column
	 * @param string $alias
	 * @return \Krystal\Db\Sql\Db
	 */
	public function min($column, $alias = null)
	{
		$this->queryBuilder->max($column, $alias);
		return $this;
	}

	/**
	 * Appends AVG() aggregate function
	 * 
	 * @param string $column Column name
	 * @param string $alias Alias
	 * @return \Krystal\Db\Sql\Db
	 */
	public function avg($column, $alias = null)
	{
		$this->queryBuilder->avg($column, $alias);
		return $this;
	}

	/**
	 * Appends SUM() aggregate function
	 * 
	 * @param string $column Column name
	 * @param string $alias Alias
	 * @return \Krystal\Db\Sql\Db
	 */
	public function sum($column, $alias = null)
	{
		$this->queryBuilder->sum($column, $alias);
		return $this;
	}

	/**
	 * Appends LEN() aggregate function
	 * 
	 * @param string $column Column name
	 * @param string $alias Optional alias
	 * @return \Krystal\Db\Sql\Db
	 */
	public function len($column, $alias = null)
	{
		$this->queryBuilder->len($column, $alias);
		return $this;
	}

	/**
	 * Appends ROUND() function
	 * 
	 * @param string $column The column to round
	 * @param float $decimals Specifies the number of decimals to be returned
	 * @return \Krystal\Db\Sql\Db
	 */
	public function round($column, $column)
	{
		$this->queryBuilder->len($column, $column);
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
	 * Appends DISTINCT expression
	 * 
	 * @return \Krystal\Db\Sql\Db
	 */
	public function distinct()
	{
		$this->queryBuilder->distinct();
		return $this;
	}

	/**
	 * Appends FROM expression
	 * 
	 * @param string $table
	 * @return \Krystal\Db\Sql\Db
	 */
	public function from($table = null)
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

	/**
	 * Appends where clause with equality operator
	 * 
	 * @param string $column
	 * @param string $value
	 * @param boolean $filter Whether to filter by empty value
	 * @return \Krystal\Db\Sql\Db
	 */
	public function whereNotEquals($column, $value, $filter = false)
	{
		return $this->where($column, '!=', $value, $filter);
	}

	/**
	 * Appends WHERE clause with > operator
	 * 
	 * @param string $column
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\Db
	 */
	public function whereGreaterThan($column, $value, $filter = false)
	{
		return $this->where($column, '>', $value, $filter);
	}

	/**
	 * Appends WHERE clause with < operator
	 * 
	 * @param string $column
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\Db
	 */
	public function whereLessThan($column, $value, $filter = false)
	{
		return $this->where($column, '<', $value, $filter);
	}
	
	/**
	 * Appends WHERE clause with "less than" operator
	 * 
	 * @param string $column
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function orWhereLessThan($column, $value, $filter = false)
	{
		return $this->orWhere($column, '<', $value, $filter);
	}
	
	/**
	 * Appends WHERE with "like" operator
	 * 
	 * @param string $column
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function andWhereLike($column, $value, $filter = false)
	{
		return $this->andWhere($column, 'LIKE', $value, $filter);
	}
	
	/**
	 * Appends OR WHERE LIKE condition
	 * 
	 * @param string $column
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function orWhereLike($column, $value, $filter = false)
	{
		return $this->orWhere($column, 'LIKE', $value, $filter);
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
	 * Opens a bracket 
	 * 
	 * @return \Krystal\Db\Sql\Db
	 */
	public function openBracket()
	{
		$this->queryBuilder->openBracket();
		return $this;
	}

	/**
	 * Closes the bracket
	 * 
	 * @return \Krystal\Db\Sql\Db
	 */
	public function closeBracket()
	{
		$this->queryBuilder->closeBracket();
		return $this;
	}

	/**
	 * Appends UNION
	 * 
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function union()
	{
		$this->queryBuilder->union();
		return $this;
	}

	/**
	 * Appends UNION ALL
	 * 
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function unionAll()
	{
		$this->queryBuilder->unionAll();
		return $this;
	}

	/**
	 * Appends AS with provided alias
	 * 
	 * @param string $alias
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function asAlias($alias)
	{
		$this->queryBuilder->asAlias($alias);
		return $this;
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
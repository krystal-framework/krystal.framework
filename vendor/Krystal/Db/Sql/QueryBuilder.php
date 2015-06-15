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

use Krystal\Stdlib\ArrayUtils;
use InvalidArgumentException;
use LogicException;

/**
 * This class is only responsible for building query strings.
 */
final class QueryBuilder implements QueryBuilderInterface, QueryObjectInterface
{
	/**
	 * Current query string
	 * 
	 * @var string
	 */
	private $queryString;

	/**
	 * Selected columns (or data)
	 * 
	 * @var string
	 */
	private $selected;

	/**
	 * Sets query raw string
	 * 
	 * @param string $queryString
	 * @return void
	 */
	public function setQueryString($queryString)
	{
		$this->queryString = $queryString;
	}

	/**
	 * Guesses count query
	 * 
	 * @return string Guessed query
	 */
	public function guessCountQuery()
	{
		return str_replace($this->selected, 'COUNT(1) AS `count`', $this->getQueryString());
	}

	/**
	 * Returns current query string
	 * 
	 * @return string
	 */
	public function getQueryString()
	{
		return $this->queryString;
	}

	/**
	 * Appends SQL function
	 * 
	 * @param string $func Function name
	 * @param string $column Column name to be passed as an argument to a function
	 * @param string $alias 
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	private function func($func, $column, $alias = null)
	{
		if (is_null($alias)) {
			$this->append(sprintf(' %s(%s) ', $func, $this->wrap($column)));
		} else {
			$this->append(sprintf(' %s(%s) AS %s ', $func, $this->wrap($column), $this->wrap($alias)));
		}

		return $this;
	}

	/**
	 * Clears the query string
	 * 
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function clear()
	{
		$this->queryString = '';
		return $this;
	}

	/**
	 * Appends a part to query string
	 * 
	 * @param string $part
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	private function append($part)
	{
		$this->queryString .= $part;
		return $this;
	}

	/**
	 * Wraps a string into back-ticks
	 * 
	 * @param string|array $target Target column name to be wrapped
	 * @throws \InvalidArgumentException If unknown type supplied
	 * @return string
	 */
	private function wrap($target)
	{
		$wrapper = function($column) {
			return sprintf('`%s`', $column);
		};

		if (is_array($target)) {
			foreach($target as &$column) {
				$column = $wrapper($column);
			}

		} else if (is_string($target) || is_integer($target)) {
			$target = $wrapper($target);

		} else {
			throw new InvalidArgumentException(sprintf(
				'Unknown type for wrapping supplied "%s"', gettype($target)
			));
		}

		return $target;
	}

	/**
	 * Sets a raw query
	 * 
	 * @param string $query Raw query
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function raw($query)
	{
		$this->queryString = $query;
		return $this;
	}

	/**
	 * Builds INSERT query
	 * This regular insert query for most cases. It's not aware of ON DUPLICATE KEY
	 * 
	 * @param string $table
	 * @param array $data Simply key value pair (without placeholders)
	 * @param boolean $ignore Whether to ignore when PK collisions occur
	 * @throws \LogicException if $data array is empty
	 * @return boolean
	 */
	public function insert($table, array $data, $ignore = false)
	{
		$keys = array();
		$values = array_values($data);

		if (empty($data)) {
			throw new LogicException('You have not provided a data to be inserted');
		}

		foreach (array_keys($data) as $key) {
			$keys[] = $this->wrap($key);
		}

		// Handle ignore case
		if ($ignore === true) {
			$ignore = 'IGNORE';
		} else {
			$ignore = '';
		}
		
		// Build and append query we made
		$this->append(sprintf('INSERT %s INTO `%s` (%s) VALUES (%s)', $ignore, $table, implode(', ', $keys), implode(', ', $values)));
		return $this;
	}

	/**
	 * Builds UPDATE query
	 * 
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function update($table, array $data)
	{
		$conditions = array();

		// Prepare conditions for SET
		foreach ($data as $key => $value) {
			$conditions[] = sprintf('`%s` = %s', $key, $value);
		}

		$query = sprintf('UPDATE `%s` SET %s', $table, implode(', ', $conditions));
		$this->append($query);

		return $this;
	}

	/**
	 * Increments a value of a column
	 * 
	 * @param string $table
	 * @param string $column
	 * @param integer $step
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function increment($table, $column, $step = 1)
	{
	}

	/**
	 * Decrements a value of a column
	 * 
	 * @param string $table
	 * @param string $column
	 * @param integer $step
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function decrement($table, $column, $step = 1)
	{
	}

	/**
	 * Appends MAX() aggregate function
	 * 
	 * @param string $column Column name
	 * @param string $alias Optional alias
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function max($column, $alias = null)
	{
		return $this->func('MAX', $column, $alias);
	}

	/**
	 * Appends MIN() aggregate function
	 * 
	 * @param string $column Column name
	 * @param string $alias Optional alias
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function min($column, $alias = null)
	{
		return $this->func('MIN', $column, $alias);
	}

	/**
	 * Appends AVG() aggregate function
	 * 
	 * @param string $column Column name
	 * @param string $alias Alias
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function avg($column, $alias = null)
	{
		return $this->func('AVG', $column, $alias);
	}

	/**
	 * Appends SUM() aggregate function
	 * 
	 * @param string $column Column name
	 * @param string $alias Alias
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function sum($column, $alias = null)
	{
		return $this->func('SUM', $column, $alias);
	}

	/**
	 * Appends COUNT() aggregate function
	 * 
	 * @param string $column
	 * @param string $alias Alias
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function count($column, $alias = null)
	{
		return $this->func('COUNT', $column, $alias);
	}

	/**
	 * Appends LEN() aggregate function
	 * 
	 * @param string $column Column name
	 * @param string $alias Optional alias
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function len($column, $alias = null)
	{
		return $this->func('LEN', $column, $alias);
	}

	/**
	 * Appends ROUND() function
	 * 
	 * @param string $column The column to round
	 * @param float $decimals Specifies the number of decimals to be returned
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function round($column, $decimals)
	{
		$this->append(sprintf(' ROUND(%s, %s) ', $this->wrap($column), $decimals));
		return $this;
	}

	/**
	 * Appends sorting condition
	 * 
	 * @param string $sort
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function sort($sort)
	{
		$this->append(sprintf(' %s ', $sort));
		return $this;
	}

	/**
	 * Appends SELECT expression
	 * 
	 * @param mixed $type
	 * @param boolean $distinct
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function select($type = null, $distinct = false)
	{
		$type = $this->getSelectType($type);

		if ($distinct === true) {
			$this->append('SELECT DISTINCT ' . $type);
		} else {
			$this->append('SELECT ' . $type);
		}

		// Do save only for the first select
		if (is_null($this->selected)) {
			// Save selected data
			$this->selected = $type;
		}

		return $this;
	}

	/**
	 * Returns expression which needs to be followed right after SELECT
	 * 
	 * @param mixed $type
	 * @return string
	 */
	private function getSelectType($type)
	{
		// * is a special keyword, which doesn't need to be wrapped
		if ($type !== '*' && $type !== null && !is_array($type)) {
			$type = $this->wrap($type);
		}

		// Special case when $type is array
		if (is_array($type)) {
			if (ArrayUtils::hasAtLeastOneArrayValue($type)) {
				$collection = array();

				foreach ($type as $key => $value) {
					// Did we receive an alias?
					if (is_array($value)) {
						foreach ($value as $column => $alias) {
							array_push($collection, sprintf('%s AS %s', $this->wrap($column), $this->wrap($alias)));
						}

					} else {
						array_push($collection, $value);
					}
				}

				$type = $collection;
			}

			// And finally, separate via commas
			$type = implode(', ', $type);
		}

		return $type;
	}

	/**
	 * Appends LIMIT expression
	 * 
	 * @param integer $offset
	 * @param integer $amount Amount of rows to be returned
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function limit($offset, $amount = null)
	{
		if (is_null($amount)) {
			$this->append(' LIMIT ' . $offset);
		} else {
			$this->append(sprintf(' LIMIT %s, %s', $offset, $amount));
		}

		return $this;
	}

	/**
	 * Appends FROM expression
	 * 
	 * @param string $table Optional table name
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function from($table = null)
	{
		if ($table !== null) {
			$table = $this->wrap($table);
		}

		$this->append(' FROM ' . $table);
		return $this;
	}

	/**
	 * Appends distinct constraint to select
	 * 
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function distinct()
	{
		$this->append(' DISTINCT ');
		return $this;
	}

	/**
	 * Appends WHERE expression
	 * 
	 * @param string $column
	 * @param string $operator
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function where($column, $operator, $value, $filter = false)
	{
		if ($filter === true && empty($value)) {
			return $this;
		}

		$this->append(sprintf(' WHERE %s %s %s ', $this->wrap($column), $operator, $value));
		return $this;
	}

	/**
	 * Appends AND WHERE expression
	 * 
	 * @param string $key
	 * @param string $operator
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function andWhere($key, $operator, $value, $filter = false)
	{
		if ($filter === true && empty($value)) {
			return $this;
		}

		$this->append(sprintf('AND %s %s %s ', $this->wrap($key), $operator, $value));
		return $this;
	}

	/**
	 * Appends AND WHERE expression
	 * 
	 * @param string $key
	 * @param string $operator
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function orWhere($key, $operator, $value, $filter = false)
	{
		if ($filter === true && empty($value)) {
			return $this;
		}

		$this->append(sprintf(' OR %s %s %s ', $this->wrap($key), $operator, $value));
		return $this;
	}

	/**
	 * Appends WHERE expression with equality operator
	 * 
	 * @param string $column
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function whereEquals($column, $value, $filter = false)
	{
		return $this->where($column, '=', $value, $filter);
	}

	/**
	 * Appends WHERE expression with non-equality operator
	 * 
	 * @param string $column
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\QueryBuilder
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
	 * @return \Krystal\Db\Sql\QueryBuilder
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
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function whereLessThan($column, $value, $filter = false)
	{
		return $this->where($column, '<', $value, $filter);
	}

	/**
	 * Appends WHERE clause with "Greater than or equals" operator
	 * 
	 * @param string $column
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function whereEqualsOrGreaterThan($column, $value, $filter = false)
	{
		return $this->where($column, '>=', $value, $filter);
	}

	/**
	 * Appends WHERE clause with "Less than or equals" operator
	 * 
	 * @param string $column
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function whereEqualsOrLessThan($column, $value, $filter = false)
	{
		return $this->where($column, '<=', $value, $filter);
	}

	/**
	 * Appends WHERE LIKE condition
	 * 
	 * @param string $column
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function whereLike($column, $value, $filter = false)
	{
		return $this->where($column, 'LIKE', $value, $filter);
	}

	/**
	 * Appends WHERE clause with "greater than" operator
	 * 
	 * @param string $column
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function andWhereGreaterThan($column, $value, $filter = false)
	{
		return $this->andWhere($column, '>', $value, $filter);
	}

	/**
	 * Appends OR WHERE clause with "greater than" operator
	 * 
	 * @param string $column
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function orWhereGreaterThan($column, $value, $filter = false)
	{
		return $this->orWhere($column, '>', $value, $filter);
	}

	/**
	 * Appends WHERE clause with less than operator
	 * 
	 * @param string $column
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function andWhereLessThan($column, $value, $filter = false)
	{
		return $this->andWhere($column, '<', $value, $filter);
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
	 * Appends AND WHERE clause with equality operator
	 * 
	 * @param string $column
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function andWhereEquals($column, $value, $filter = false)
	{
		return $this->andWhere($column, '=', $value, $filter);
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

	/**
	 * Appends RAND() function
	 * 
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function rand()
	{
		$this->append(' RAND() ');
		return $this;
	}
	
	public function having()
	{
		//@TODO
	}

	public function groupBy()
	{
		//@TODO
	}

	/**
	 * Appends ORDER BY expression
	 * 
	 * @param string|array $type
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */ 
	public function orderBy($type = null)
	{
		if ($type === null) {
			$this->append(' ORDER BY ');
		} else {
			$this->append(' ORDER BY '.$this->wrap($type));
		}

		return $this;
	}

	/**
	 * Appends DESC condition
	 * 
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function desc()
	{
		$this->append(' DESC ');
		return $this;
	}
	
	/**
	 * Appends WHERE IN expression
	 * 
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function whereIn($key, array $set)
	{
		//@TODO
		return $this;
	}

	/**
	 * @param string $key
	 * @param array $set
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function whereNotIn($key, array $set)
	{
		//@TODO 
		return $this;
	}

	/**
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function whereBetween($key, array $pair)
	{
		//@TODO
		return $this;
	}

	/**
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function whereNotBetween($key, array $pair)
	{
		//@TODO
		return $this;
	}

	/**
	 * Appends DELETE expression
	 * 
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function delete()
	{
		$this->append(' DELETE ');
		return $this;
	}

	/**
	 * Opens a bracket 
	 * 
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function openBracket()
	{
		$this->append('(');
		return $this;
	}

	/**
	 * Closes the bracket
	 * 
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function closeBracket()
	{
		$this->append(')');
		return $this;
	}

	/**
	 * Appends UNION
	 * 
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function union()
	{
		$this->append(' UNION ');
		return $this;
	}

	/**
	 * Appends UNION ALL
	 * 
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function unionAll()
	{
		$this->append(' UNION ALL ');
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
		$this->append(sprintf(' AS %s', $this->wrap($alias)));
		return $this;
	}
	
	/**
	 * Appends TRUNCATE statement
	 * 
	 * @param string $table
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function truncate($table)
	{
		$this->append(sprintf('TRUNCATE %s', $this->wrap($table)));
		return $this;
	}
}

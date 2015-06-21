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

interface QueryBuilderInterface
{
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
	public function insert($table, array $data, $ignore = false);

	/**
	 * Builds UPDATE query
	 * 
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function update($table, array $data);

	/**
	 * Increments a value of a column
	 * 
	 * @param string $table
	 * @param string $column
	 * @param integer $step
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function increment($table, $column, $step = 1);

	/**
	 * Decrements a value of a column
	 * 
	 * @param string $table
	 * @param string $column
	 * @param integer $step
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function decrement($table, $column, $step = 1);

	/**
	 * Appends MAX() aggregate function
	 * 
	 * @param string $column Column name
	 * @param string $alias Optional alias
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function max($column, $alias = null);

	/**
	 * Appends MIN() aggregate function
	 * 
	 * @param string $column Column name
	 * @param string $alias Optional alias
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function min($column, $alias = null);

	/**
	 * Appends AVG() aggregate function
	 * 
	 * @param string $column Column name
	 * @param string $alias Alias
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function avg($column, $alias = null);

	/**
	 * Appends SUM() aggregate function
	 * 
	 * @param string $column Column name
	 * @param string $alias Alias
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function sum($column, $alias = null);

	/**
	 * Appends COUNT() aggregate function
	 * 
	 * @param string $column
	 * @param string $alias Alias
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function count($column, $alias = null);

	/**
	 * Appends DELETE expression
	 * 
	 * @return \Krystal\Db\Sql\Db
	 */
	public function delete();

	/**
	 * Appends LEN() aggregate function
	 * 
	 * @param string $column Column name
	 * @param string $alias Optional alias
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function len($column, $alias = null);

	/**
	 * Appends ROUND() function
	 * 
	 * @param string $column The column to round
	 * @param float $decimals Specifies the number of decimals to be returned
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function round($column, $decimals);

	/**
	 * Appends SELECT expression
	 * 
	 * @param mixed $type
	 * @param boolean $distinct
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function select($type = null, $distinct = false);

	/**
	 * Appends LIMIT expression
	 * 
	 * @param integer $offset
	 * @param integer $amount Amount of rows to be returned
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function limit($offset, $amount = null);

	/**
	 * Appends FROM expression
	 * 
	 * @param string $table Optional table name
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function from($table = null);

	/**
	 * Appends distinct constraint to select
	 * 
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function distinct();

	/**
	 * Appends WHERE expression
	 * 
	 * @param string $column
	 * @param string $operator
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function where($column, $operator, $value, $filter = false);

	/**
	 * Appends AND WHERE expression
	 * 
	 * @param string $key
	 * @param string $operator
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function andWhere($key, $operator, $value, $filter = false);

	/**
	 * Appends AND WHERE expression
	 * 
	 * @param string $key
	 * @param string $operator
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function orWhere($key, $operator, $value, $filter = false);

	/**
	 * Appends WHERE expression with equality operator
	 * 
	 * @param string $column
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function whereEquals($column, $value, $filter = false);

	/**
	 * Appends WHERE expression with non-equality operator
	 * 
	 * @param string $column
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function whereNotEquals($column, $value, $filter = false);

	/**
	 * Appends WHERE clause with > operator
	 * 
	 * @param string $column
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function whereGreaterThan($column, $value, $filter = false);

	/**
	 * Appends WHERE clause with > operator
	 * 
	 * @param string $column
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function whereLessThan($column, $value, $filter = false);

	/**
	 * Appends WHERE clause with "less than" operator
	 * 
	 * @param string $column
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function orWhereLessThan($column, $value, $filter = false);

	/**
	 * Appends AND WHERE clause with equality operator
	 * 
	 * @param string $column
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function andWhereEquals($column, $value, $filter = false);

	/**
	 * Appends WHERE with "like" operator
	 * 
	 * @param string $column
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function andWhereLike($column, $value, $filter = false);

	/**
	 * Appends OR WHERE LIKE condition
	 * 
	 * @param string $column
	 * @param string $value
	 * @param boolean $filter Whether to filter by value
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function orWhereLike($column, $value, $filter = false);

	/**
	 * Appends RAND() function
	 * 
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function rand();

	/**
	 * Appends INNER JOIN
	 * 
	 * @param string $table Right table (second)
	 * @param string $left A column from the left table (first)
	 * @param string $right A column from the right table
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function innerJoin($table, $left, $right);

	/**
	 * Appends LEFT JOIN
	 * 
	 * @param string $table Right table (second)
	 * @param string $left A column from the left table (first)
	 * @param string $right A column from the right table
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function leftJoin($table, $left, $right);

	/**
	 * Appends RIGHT JOIN
	 * 
	 * @param string $table Right table (second)
	 * @param string $left A column from the left table (first)
	 * @param string $right A column from the right table
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function rightJoin($table, $left, $right);

	/**
	 * Append FULL OUTER JOIN
	 *
	 * @param string $table Right table (second)
	 * @param string $left A column from the left table (first)
	 * @param string $right A column from the right table
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function fullJoin($table, $left, $right);

	/**
	 * Appends ORDER BY expression
	 * 
	 * @param string|array $type
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */ 
	public function orderBy($type = null);

	/**
	 * Appends DESC condition
	 * 
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function desc();

	/**
	 * Opens a bracket 
	 * 
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function openBracket();

	/**
	 * Appends UNION
	 * 
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function union();

	/**
	 * Appends AS with provided alias
	 * 
	 * @param string $alias
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function asAlias($alias);

	/**
	 * Appends TRUNCATE statement
	 * 
	 * @param string $table
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function truncate($table);
}

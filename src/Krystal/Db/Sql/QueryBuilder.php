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
     * Selected table name
     * 
     * @var string
     */
    private $table;

    /**
     * Returns a name of selected table
     * 
     * @throws \LogicException if no table is selected
     * @return string
     */
    public function getSelectedTable()
    {
        if (is_null($this->table)) {
            throw new LogicException('A table was not selected. Make sure you call from() passing table name as its first argument');
        } else {
            return $this->table;
        }
    }

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
     * @param string $column Column to be selected
     * @param string $alias
     * @return string Guessed count query
     */
    public function guessCountQuery($column, $alias)
    {
        return str_replace($this->selected, $this->getFunction('COUNT', $column, $alias), $this->getQueryString());
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
     * Prepares a wildcard
     * 
     * @param string $value
     * @return string
     */
    public function prepareWildcard($value)
    {
        // For now doing nothing, since it might build query incorrectly in case a value was empty
        return $value;

        // If value has only wildcards, then it should be considered as empty
        if (in_array($value, array('%', '%%'))) {
            return '';
        } else {
            // Otherwise, that's a value which might contain wildcards, so nothing to do here
            return $value;
        }
    }

    /**
     * Checks whether it's worth filtering
     * 
     * @param boolean $state
     * @param string|array $target
     * @return boolean
     */
    public function isFilterable($state, $target)
    {
        // Start checking from very common usage case
        if ($state == true && $target == '0') {
            return true;
        }

        $result = false;

        if ($state === false) {
            $result = true;
        } else {

            if (is_string($target) && $target != '0' && !empty($target)) {
                $result = true;
            }

            if (is_array($target)) {
                // If empty array is passed, that means it's time to stop right here
                if (empty($target)) {
                    $result = false;
                } else {
                    // Otherwise go on
                    $count = 0;

                    foreach ($target as $value) {
                        if (!empty($value)) {
                            $count++;
                        }
                    }

                    // All values must not be empty
                    if ($count == count($target)) {
                        $result = true;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Appends SHOW KEYS expression
     * 
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function showKeys()
    {
        $this->append(' SHOW KEYS ');
        return $this;
    }

    /**
     * Generates SQL function fragment
     * 
     * @param string $func Function name
     * @param string $column Column name to be passed as an argument to a function
     * @param string $alias 
     * @return string
     */
    private function getFunction($func, $column, $alias = null)
    {
        if (is_null($alias)) {
            return sprintf(' %s(%s) ', $func, $this->wrap($column));
        } else {
            return sprintf(' %s(%s) AS %s ', $func, $this->wrap($column), $this->wrap($alias));
        }
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
        $this->append($this->getFunction($func, $column, $alias));
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
        // Lazy initialization
        static $wrapper = null;

        if (is_null($wrapper)) {
            $wrapper = new ColumnWrapper();
        }

        return $wrapper->wrap($target);
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
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function insert($table, array $data, $ignore = false)
    {
        if (empty($data)) {
            throw new LogicException('You have not provided a data to be inserted');
        }

        $keys = array();
        $values = array_values($data);

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
     * Generate INSERT query for many records
     * 
     * @param string $table
     * @param array $columns
     * @param array $values
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function insertMany($table, array $columns, array $values)
    {
        // Escape column names
        foreach ($columns as &$column) {
            $column = $this->wrap($column);
        }

        // Validate the length
        foreach ($values as $data) {
            $dataCount = count($data);
            $columnsCount = count($columns);

            if ($dataCount !== $columnsCount) {
                throw new Exception(sprintf('Count mismatch. One collection contains %s keys instead of expected %s', $dataCount, $columnsCount));
            }
        }

        // Generate initial fragment
        $sql = sprintf('INSERT INTO %s (%s) VALUES ', $table, implode(', ', $columns));

        // Generate the rest
        foreach ($values as $data) {
            if (is_array($data)) {
                $sql .= sprintf("(%s), ", implode(', ', $data));
            } else {
                throw new Exception(sprintf('3-rd argument should contain a collection of arrays, one of them is %s', gettype($data)));
            }
        }

        // Tweak the ending fragment
        $sql = substr($sql, 0, -2);
        $sql .= ';';

        // Done there
        $this->append($sql);
        return $this;
    }

    /**
     * Builds UPDATE query
     * 
     * @param string $table
     * @param array $data Data to be updated
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function update($table, array $data)
    {
        $conditions = array();

        foreach ($data as $key => $value) {
            // Wrap column names into back-ticks
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
        // Make sure expected value is going to be updated
        $step = (int) $step;
        $step = (string) $step;

        return $this->update($table, array($column => $column.' + '.$step));
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
        // Make sure expected value is going to be updated
        $step = (int) $step;
        $step = (string) $step;

        return $this->update($table, array($column => $column.' - '.$step));
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
        $type = $this->createSelectData($type);

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
    private function createSelectData($type)
    {
        // * is a special keyword, which doesn't need to be wrapped
        if ($type !== '*' && $type !== null && !is_array($type)) {
            $type = $this->wrap($type);
        }

        // Special case when $type is array
        if (is_array($type)) {
            $collection = array();

            foreach ($type as $column => $alias) {
                // Did we receive an alias?
                if (!is_numeric($column)) {
                    $push = sprintf('%s AS %s', $this->wrap($column), $this->wrap($alias));
                } else {
                    $push = $alias;
                }

                array_push($collection, $push);
            }

            // And finally, separate via commas
            $type = implode(', ', $collection);
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
            $this->table = $table;
            $table = $this->wrap($table);
        }

        $this->append(' FROM ' . $table);
        return $this;
    }

    /**
     * Appends a raw comparison
     * 
     * @param string $column
     * @param string $operator
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function compare($column, $operator, $value, $filter = false)
    {
        if (!$this->isFilterable($filter, $value)) {
            return $this;
        }

        $this->append(sprintf(' %s %s %s ', $this->wrap($column), $operator, $value));
        return $this;
    }

    /**
     * Appends a raw comparison with = operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function equals($column, $value, $filter = false)
    {
        return $this->compare($column, '=', $value, $filter);
    }

    /**
     * Appends a raw comparison with != operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function notEquals($column, $value, $filter = false)
    {
        return $this->compare($column, '!=', $value, $filter);
    }

    /**
     * Appends a raw comparison with LIKE operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function like($column, $value, $filter = false)
    {
        return $this->compare($column, 'LIKE', $value, $filter);
    }

    /**
     * Appends a raw comparison with NOT LIKE operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function notLike($column, $value, $filter = false)
    {
        return $this->compare($column, 'NOT LIKE', $value, $filter);
    }

    /**
     * Appends a raw comparison with > operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function greaterThan($column, $value, $filter = false)
    {
        return $this->compare($column, '>', $value, $filter);
    }

    /**
     * Appends a raw comparison with < operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function lessThan($column, $value, $filter = false)
    {
        return $this->compare($column, '<', $value, $filter);
    }

    /**
     * Appends a raw comparison with >= operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function greaterThanOrEquals($column, $value, $filter = false)
    {
        return $this->compare($column, '>=', $value, $filter);
    }

    /**
     * Appends a raw comparison with >= operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function lessThanOrEquals($column, $value, $filter = false)
    {
        return $this->compare($column, '<=', $value, $filter);
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
        if (!$this->isFilterable($filter, $value)) {
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
        if (!$this->isFilterable($filter, $value)) {
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
        if (!$this->isFilterable($filter, $value)) {
            return $this;
        }

        $this->append(sprintf(' OR %s %s %s ', $this->wrap($key), $operator, $value));
        return $this;
    }

    /**
     * Appends OR WHERE expression with equality operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function orWhereEquals($column, $value, $filter = false)
    {
        return $this->orWhere($column, '=', $value, $filter);
    }

    /**
     * Appends OR WHERE with != operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function orWhereNotEquals($column, $value, $filter = false)
    {
        return $this->orWhere($column, '!=', $value, $filter);
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
     * Appends OR WHERE NOT LIKE condition
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function orWhereNotLike($column, $value, $filter = false)
    {
        return $this->orWhere($column, 'NOT LIKE', $value, $filter);
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
     * Appends OR WHERE with >= operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function orWhereGreaterThanOrEquals($column, $value, $filter = false)
    {
        return $this->orWhere($column, '>=', $value, $filter);
    }

    /**
     * Appends OR WHERE with <= operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function orWhereLessThanOrEquals($column, $value, $filter = false)
    {
        return $this->orWhere($column, '<=', $value, $filter);
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
     * Appends WHERE with "NOT LIKE" operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function andWhereNotLike($column, $value, $filter = false)
    {
        return $this->andWhere($column, 'NOT LIKE', $value, $filter);
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
     * Appends AND WHERE clause with equality operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function andWhereNotEquals($column, $value, $filter = false)
    {
        return $this->andWhere($column, '!=', $value, $filter);
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
     * Appends AND WHERE clause with less than operator
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
     * Appends AND WHERE with >= operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function andWhereEqualsOrGreaterThan($column, $value, $filter = false)
    {
        return $this->andWhere($column, '>=', $value, $filter);
    }

    /**
     * Appends AND WHERE with <= operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function andWhereEqualsOrLessThan($column, $value, $filter = false)
    {
        return $this->andWhere($column, '<=', $value, $filter);
    }

    /**
     * Appends NOW() function
     * 
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function now()
    {
        $this->append(' NOW() ');
        return $this;
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

    /**
     * Appends JOIN statement
     * 
     * @param string $type JOIN type
     * @param string $table Right table (second)
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    private function join($type, $table)
    {
        $this->append(sprintf(' %s JOIN %s ', $type, $table));
        return $this;
    }

    /**
     * Appends INNER JOIN
     * 
     * @param string $table Right table (second)
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function innerJoin($table)
    {
        return $this->join('INNER', $table);
    }

    /**
     * Appends LEFT JOIN
     * 
     * @param string $table Right table (second)
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function leftJoin($table)
    {
        return $this->join('LEFT', $table);
    }

    /**
     * Appends RIGHT JOIN
     * 
     * @param string $table Right table (second)
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function rightJoin($table)
    {
        return $this->join('RIGHT', $table);
    }

    /**
     * Append FULL OUTER JOIN
     *
     * @param string $table Right table (second)
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function fullJoin($table)
    {
        return $this->join('FULL OUTER', $table);
    }

    /**
     * Appends ON
     * 
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function on()
    {
        $this->append(' ON ');
        return $this;
    }

    /**
     * Appends HAVING() clause
     * 
     * @param string $function Aggregate function
     * @param string $column
     * @param string $operator
     * @param string $value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function having($function, $column, $operator, $value)
    {
        $this->append(sprintf(' HAVING %s(%s) %s %s ', $function, $column, $operator, $value));
        return $this;
    }

    /**
     * Appends GROUP BY statement
     * 
     * @param string|array $target
     * @throws \InvalidArgumentException If $target isn't either a string or an array
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function groupBy($target)
    {
        $columns = null;

        if (is_string($target)) {
            $columns = $target;

        } elseif (is_array($target)) {
            $columns = implode(', ', $target);

        } else {
            throw new InvalidArgumentException(sprintf(
                'groupBy() accepts only an array of columns or a plain column name. You supplied "%s"', gettype($target)
            ));
        }

        $this->append(sprintf(' GROUP BY %s ', $columns));
        return $this;
    }

    /**
     * Appends ORDER BY expression
     * 
     * @param string|array|\Krystal\Db\Sql\RawSqlFragmentInterface $type
     * @return \Krystal\Db\Sql\QueryBuilder
     */ 
    public function orderBy($type = null)
    {
        if ($type === null) {
            $target = null;

        } elseif ($type instanceof RawSqlFragmentInterface) {
            $target = $type->getFragment();

        } elseif (is_array($type)) {

            if (!ArrayUtils::isAssoc($type)) {
                // Special case for non-associative array
                foreach ($type as &$column) {
                    $column = $this->wrap($column);
                }
                
            } else {
                // If associative array supplied then assume that values represent sort orders
                $result = array();

                foreach ($type as $column => $sortOrder) {
                    // Only column names should be wrapped around backticks
                    array_push($result, sprintf('%s %s', $this->wrap($column), $sortOrder));
                }

                $type = $result;
            }

            $target = implode(', ', $type);

        } else {
            $target = $this->wrap($type);
        }

        $this->append(' ORDER BY '.$target);
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
     * Appends ASC condition
     * 
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function asc()
    {
        $this->append(' ASC ');
        return $this;
    }

    /**
     * Appends WHERE IN (..) expression
     * 
     * @param string $column
     * @param array $values
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function whereIn($column, array $values, $filter = false)
    {
        return $this->whereInValues($column, $values, $filter);
    }

    /**
     * Internal method to build WHERE IN ()
     * 
     * @param string $key
     * @param array $values
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    private function whereInValues($column, array $values, $filter)
    {
        if (!$this->isFilterable($filter, $values)) {
            return $this;
        }

        $this->append(sprintf(' WHERE `%s` IN (%s)', $column, implode(', ', $values)));
        return $this;
    }

    /**
     * Appends WHERE with BETWEEN operator
     * 
     * @param string $column
     * @param string $a First value
     * @param string $b Second value
     * @param boolean $not Whether it must be NOT BETWEEN or not
     * @param string $operator Optional operator to be prep-ended before WHERE clause
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    private function between($column, $a, $b, $not = false, $operator = null, $filter = false)
    {
        if (!$this->isFilterable($filter, array($a, $b))) {
            return $this;
        }

        if ($operator !== null) {
            // A space before the operator is preferred
            $operator = sprintf(' %s', $operator);
        }

        // Handle NOT prefix
        if ($not === true) {
            $not = 'NOT';
        } else {
            $not = '';
        }

        $this->append($operator.sprintf(' WHERE `%s` %s BETWEEN %s AND %s ', $column, $not, $a, $b));
        return $this;
    }

    /**
     * Appends OR WHERE with BETWEEN operator
     *
     * @param string $column
     * @param string $a First value
     * @param string $b Second value
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function orWhereBetween($column, $a, $b, $filter = false)
    {
        return $this->between($column, $a, $b, false, 'OR', $filter);
    }

    /**
     * Appends AND WHERE with BETWEEN operator
     *
     * @param string $column
     * @param string $a First value
     * @param string $b Second value
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function andWhereBetween($column, $a, $b, $filter = false)
    {
        return $this->between($column, $a, $b, false, 'AND', $filter);
    }

    /**
     * Appends WHERE with BETWEEN operator
     * 
     * @param string $column
     * @param string $a First value
     * @param string $b Second value
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function whereBetween($column, $a, $b, $filter = false)
    {
        return $this->between($column, $a, $b, false, null, $filter);
    }

    /**
     * Appends AND WHERE with NOT BETWEEN operator
     * 
     * @param string $column
     * @param string $a First value
     * @param string $b Second value
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function andWhereNotBetween($column, $a, $b, $filter = false)
    {
        return $this->between($column, $a, $b, true, 'AND', $filter);
    }

    /**
     * Appends AND WHERE with NOT BETWEEN operator
     * 
     * @param string $column
     * @param string $a First value
     * @param string $b Second value
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function orWhereNotBetween($column, $a, $b, $filter = false)
    {
        return $this->between($column, $a, $b, true, 'OR', $filter);
    }

    /**
     * Appends WHERE with NOT BETWEEN operator
     *
     * @param string $column
     * @param string $a First value
     * @param string $b Second value
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function whereNotBetween($column, $a, $b, $filter = false)
    {
        return $this->between($column, $a, $b, true, $filter);
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
     * Appends raw AND
     * 
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function rawAnd()
    {
        $this->append(' AND ');
        return $this;
    }

    /**
     * Appends raw OR
     * 
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function rawOr()
    {
        $this->append(' OR ');
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

    /**
     * Appends "RENAME TO" statement
     * 
     * @param string $old
     * @param string $new
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function renameTable($old, $new)
    {
        $this->append(sprintf('RENAME TABLE %s TO %s ', $this->wrap($old), $this->wrap($new)));
        return $this;
    }

    /**
     * Generates DROP TABLE statement
     * 
     * @param string|array $table Table name
     * @param boolean $ifExists Whether to generate IF EXIST condition as well
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function dropTable($target, $ifExists = true)
    {
        $this->append('DROP TABLE');

        if ($ifExists === true) {
            $this->append(' IF EXISTS');
        }

        if (!is_array($target)) {
            $target = array($target);
        }

        foreach ($target as &$table) {
            $table = $this->wrap($table);
        }

        $this->append(sprintf(' %s', implode(', ', $target)));
        return $this;
    }

    /**
     * Appends "ALTER TABLE" statement
     * 
     * @param string $table
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function alterTable($table)
    {
        $this->append(sprintf('ALTER TABLE %s', $this->wrap($table)));
        return $this;
    }

    /**
     * Appends "ADD COLUMN" statement
     * 
     * @param string $column
     * @param string $type
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function addColumn($column, $type)
    {
        $this->append(sprintf(' ADD COLUMN %s %s', $this->wrap($column), $type));
        return $this;
    }

    /**
     * Appends "DROP COLUMN" statement
     * 
     * @param string $column
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function dropColumn($column)
    {
        $this->append(sprintf(' DROP COLUMN %s', $this->wrap($column)));
        return $this;
    }

    /**
     * Appends "RENAME COLUMN TO" statement
     * 
     * @param string $old Old name
     * @param string $new New name
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function renameColumn($old, $new)
    {
        $this->append(sprintf(' RENAME COLUMN %s TO %s ', $this->wrap($old), $this->wrap($new)));
        return $this;
    }

    /**
     * Appends "CHANGE" statement
     * 
     * @param string $column
     * @param string $type
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function alterColumn($column, $type)
    {
        $column = $this->wrap($column);

        $this->append(sprintf(' CHANGE %s %s %s ', $column, $column, $type));
        return $this;
    }
}

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

interface QueryBuilderInterface
{
    const LIKE_RAW = 1;
    const LIKE_STARTS = 2;
    const LIKE_ENDS = 3;
    const LIKE_CONTAINS = 4;

    /* Shortcuts for migration methods */
    const COLUMN_TYPE_PK = 'INT NOT NULL PRIMARY KEY AUTO_INCREMENT';
    const COLUMN_TYPE_STRING = 'VARCHAR(255)';
    const COLUMN_TYPE_BOOL = 'VARCHAR(1)';
    const COLUMN_TYPE_INT = 'INT';
    const COLUMN_TYPE_FLOAT = 'FLOAT';
    const COLUMN_TYPE_SMALLINT = 'SMALLINT(6)';
    const COLUMN_TYPE_BIGINT = 'BIGINT(20)';
    const COLUMN_TYPE_DECIMAL = 'DECIMAL';
    const COLUMN_TYPE_DATETIME = 'DATETIME';
    const COLUMN_TYPE_TIMESTAMP = 'TIMESTAMP';
    const COLUMN_TYPE_TIME = 'TIME';
    const COLUMN_TYPE_DATE = 'DATE';
    const COLUMN_TYPE_MONEY = 'DECIMAL(19,4)';
    const COLUMN_TYPE_BINARY = 'BLOB';

    /**
     * Appends a part to query string
     * 
     * @param string $part
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function append($part);

    /**
     * Appends SHOW KEYS expression
     * 
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function showKeys();

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
    public function insert($table, array $data, $ignore = false);

    /**
     * Builds and appends INSERT statement without explicit column names
     * 
     * @param string $table
     * @param array $values Values to be inseted in columns
     * @param boolean $ignore Whether to ignore when PK collisions occur
     * @throws \LogicException if $values array is empty
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function insertShort($table, array $values, $ignore = false);

    /**
     * Generate INSERT query for many records
     * 
     * @param string $table
     * @param array $columns
     * @param array $values
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function insertMany($table, array $columns, array $values);

    /**
     * Appends special INSERT statement for junction table
     * 
     * @param string $table Junction table name
     * @param array $columns
     * @param string $master Master value
     * @param array $slaves Slave keys
     * @throws \LogicException If the count of columns isn't 2
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function insertIntoJunction($table, array $columns, $master, array $slaves);

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
     * @param array $tables Optional tables
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function delete(array $tables = array());

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
     * Appends REPLACE() function
     * 
     * @param string $column
     * @param string $target
     * @param string $replacement
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function replace($column, $target, $replacement);

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
     * Appends a raw comparison
     * 
     * @param string $column
     * @param string $operator
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function compare($column, $operator, $value, $filter = false);

    /**
     * Appends raw $column IN (..) fragment
     * 
     * @param string $column
     * @param array $values
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function in($column, array $values, $filter = false);

    /**
     * Appends a raw comparison with = operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function equals($column, $value, $filter = false);

    /**
     * Appends a raw comparison with != operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function notEquals($column, $value, $filter = false);

    /**
     * Appends a raw comparison with LIKE operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function like($column, $value, $filter = false);

    /**
     * Appends a raw comparison with NOT LIKE operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function notLike($column, $value, $filter = false);

    /**
     * Appends OR WHERE NOT LIKE condition
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function orWhereNotLike($column, $value, $filter = false);

    /**
     * Appends a raw comparison with > operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function greaterThan($column, $value, $filter = false);

    /**
     * Appends a raw comparison with < operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function lessThan($column, $value, $filter = false);

    /**
     * Appends a raw comparison with >= operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function greaterThanOrEquals($column, $value, $filter = false);

    /**
     * Appends a raw comparison with >= operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function lessThanOrEquals($column, $value, $filter = false);

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
     * Appends OR WHERE expression with equality operator
     * 
     * @param string $key
     * @param string $operator
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function orWhere($key, $operator, $value, $filter = false);

    /**
     * Appends OR WHERE with != operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function orWhereNotEquals($column, $value, $filter = false);

    /**
     * Appends OR WHERE expressions
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function orWhereEquals($column, $value, $filter = false);

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
     * Appends OR WHERE clause with "greater than" operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function orWhereGreaterThan($column, $value, $filter = false);

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
     * Appends OR WHERE with >= operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function orWhereGreaterThanOrEquals($column, $value, $filter = false);

    /**
     * Appends OR WHERE with <= operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function orWhereLessThanOrEquals($column, $value, $filter = false);

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
     * Appends AND WHERE clause with equality operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function andWhereEquals($column, $value, $filter = false);

    /**
     * Appends AND WHERE clause with equality operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function andWhereNotEquals($column, $value, $filter = false);

    /**
     * Appends AND WHERE clause with "greater than" operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function andWhereGreaterThan($column, $value, $filter = false);

    /**
     * Appends AND WHERE clause with less than operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function andWhereLessThan($column, $value, $filter = false);

    /**
     * Appends AND WHERE with >= operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function andWhereEqualsOrGreaterThan($column, $value, $filter = false);

    /**
     * Appends AND WHERE with <= operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function andWhereEqualsOrLessThan($column, $value, $filter = false);

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
     * Appends WHERE with "NOT LIKE" operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function andWhereNotLike($column, $value, $filter = false);

    /**
     * Appends WHERE column IN (..) expression
     * 
     * @param string $column
     * @param array|\Krystal\Db\Sql\RawSqlFragmentInterface $in
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function whereIn($column, $in, $filter = false);

    /**
     * Appends WHERE column NOT IN (..) expression
     * 
     * @param string $column
     * @param array|\Krystal\Db\Sql\RawSqlFragmentInterface $in
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function whereNotIn($column, $in, $filter = false);

    /**
     * Appends AND column IN (..) expression
     * 
     * @param string $column
     * @param array|\Krystal\Db\Sql\RawSqlFragmentInterface $in
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function andWhereIn($column, $in, $filter = false);

    /**
     * Appends AND column NOT IN (..) expression
     * 
     * @param string $column
     * @param array|\Krystal\Db\Sql\RawSqlFragmentInterface $in
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function andWhereNotIn($column, $in, $filter = false);

    /**
     * Appends OR column IN (..) expression
     * 
     * @param string $column
     * @param array|\Krystal\Db\Sql\RawSqlFragmentInterface $in
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function orWhereIn($column, $in, $filter = false);

    /**
     * Appends OR column NOT IN (..) expression
     * 
     * @param string $column
     * @param array|\Krystal\Db\Sql\RawSqlFragmentInterface $in
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function orWhereNotIn($column, $in, $filter = false);

    /**
     * Appends NOW() function
     * 
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function now();

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
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function innerJoin($table);

    /**
     * Appends LEFT JOIN
     * 
     * @param string $table Right table (second)
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function leftJoin($table);

    /**
     * Appends RIGHT JOIN
     * 
     * @param string $table Right table (second)
     * @param string $left A column from the left table (first)
     * @param string $right A column from the right table (second)
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function rightJoin($table);

    /**
     * Append FULL OUTER JOIN
     *
     * @param string $table Right table (second)
     * @param string $left A column from the left table (first)
     * @param string $right A column from the right table (second)
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function fullJoin($table);

    /**
     * Appends ON
     * 
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function on();

    /**
     * Appends HAVING() clause
     * 
     * @param string $function Aggregate function
     * @param string $column
     * @param string $operator
     * @param string $value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function having($function, $column, $operator, $value);

    /**
     * Appends GROUP BY statement
     * 
     * @param string|array $target
     * @throws \InvalidArgumentException If $target isn't either a string or an array
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function groupBy($target);

    /**
     * Appends ORDER BY expression
     * 
     * @param string|array|\Krystal\Db\Sql\RawSqlFragmentInterface $type
     * @return \Krystal\Db\Sql\QueryBuilder
     */ 
    public function orderBy($type = null);

    /**
     * Appends OR WHERE with BETWEEN operator
     *
     * @param string $column
     * @param string $a First value
     * @param string $b Second value
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function orWhereBetween($column, $a, $b, $filter = false);

    /**
     * Appends AND WHERE with BETWEEN operator
     *
     * @param string $column
     * @param string $a First value
     * @param string $b Second value
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function andWhereBetween($column, $a, $b, $filter = false);

    /**
     * Appends WHERE with BETWEEN operator
     * 
     * @param string $column
     * @param string $a First value
     * @param string $b Second value
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function whereBetween($column, $a, $b, $filter = false);

    /**
     * Appends AND WHERE with NOT BETWEEN operator
     * 
     * @param string $column
     * @param string $a First value
     * @param string $b Second value
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function andWhereNotBetween($column, $a, $b, $filter = false);

    /**
     * Appends AND WHERE with NOT BETWEEN operator
     * 
     * @param string $column
     * @param string $a First value
     * @param string $b Second value
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function orWhereNotBetween($column, $a, $b, $filter = false);

    /**
     * Appends WHERE with NOT BETWEEN operator
     *
     * @param string $column
     * @param string $a First value
     * @param string $b Second value
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function whereNotBetween($column, $a, $b, $filter = false);

    /**
     * Appends DESC condition
     * 
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function desc();

    /**
     * Appends ASC condition
     * 
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function asc();

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
     * Appends raw AND
     * 
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function rawAnd();

    /**
     * Appends raw OR
     * 
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function rawOr();

    /**
     * Appends TRUNCATE statement
     * 
     * @param string $table
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function truncate($table);

    /**
     * Appends "RENAME TO" statement
     * 
     * @param string $old
     * @param string $new
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function renameTable($old, $new);

    /**
     * Generates DROP TABLE statement
     * 
     * @param string|array $table Table name
     * @param boolean $ifExists Whether to generate IF EXIST condition as well
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function dropTable($table, $ifExists = true);

    /**
     * Appends "ALTER TABLE" statement
     * 
     * @param string $table
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function alterTable($table);

    /**
     * Appends "ADD COLUMN" statement
     * 
     * @param string $column
     * @param string $type
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function addColumn($column, $type);

    /**
     * Appends "DROP COLUMN" statement
     * 
     * @param string $column
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function dropColumn($column);

    /**
     * Appends "RENAME COLUMN TO" statement
     * 
     * @param string $old Old name
     * @param string $new New name
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function renameColumn($old, $new);

    /**
     * Appends "CHANGE" statement
     * 
     * @param string $column
     * @param string $type
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function alterColumn($column, $type);

    /**
     * Appends "CREATE TABLE" statement
     * 
     * @param string $table Table name
     * @param array $definitions Column definitions
     * @param string $engine Table engine
     * @param boolean $ifNotExists Whether to include IF NOT EXITS statement
     * @param string $charset Table charset
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function createTable($table, array $definitions, $engine = 'MyISAM', $ifNotExists = false, $charset = 'UTF8');

    /**
     * Appends "ADD CONSTRAINT" statement
     * 
     * @param string $name
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function addConstraint($name);

    /**
     * Appends "DROP CONSTRAINT" statement
     * 
     * @param string $name
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function dropConstraint($name);

    /**
     * Append "PRIMARY KEY ()" statement
     * 
     * @param array $columns
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function primaryKey($target);

    /**
     * Appends "CREATE INDEX" expression
     * 
     * @param string $name Index name
     * @param string $table Target table
     * @param array|string $target A collection of column names, or a single one
     * @param boolean $unique Whether to include UNIQUE constraint
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function createIndex($table, $name, $target, $unique = false);

    /**
     * Appends "DROP INDEX" expression
     * 
     * @param string $table Target table
     * @param string $name Index name
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function dropIndex($table, $name);
}

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

use PDO;
use RuntimeException;
use Krystal\Stdlib\ArrayUtils;
use Krystal\Paginate\PaginatorInterface;
use Krystal\Db\Sql\Relations\RelationProcessor;
use Krystal\Db\Sql\Relations\RelationableServiceInterface;

/* This is just a bridge between PDO and QueryBuilder, that makes it all work */
final class Db implements DbInterface, RelationableServiceInterface
{
    /**
     * Query builder
     *   
     * @var \Krystal\Db\Sql\QueryBuilderInterface
     */
    private $queryBuilder;

    /**
     * Prepared PDO instance
     * 
     * @var \Krystal\Db\Sql\LazyPDO
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
     * Processor for relational data linked across several tables
     * 
     * @var \Krystal\Db\Sql\Relations\RelationProcessor
     */
    private $relationProcessor;

    /**
     * State initialization
     * 
     * @param \Krystal\Db\Sql\QueryBuilderInterface $queryBuilder
     * @param \Krystal\Db\Sql\LazyPDO $pdo
     * @param \Krystal\Paginate\PaginatorInterface $paginator
     * @param \Krystal\Db\Sql\QueryLoggerInterface $queryLogger
     * @return void
     */
    public function __construct(QueryBuilderInterface $queryBuilder, LazyPDO $pdo, PaginatorInterface $paginator, QueryLoggerInterface $queryLogger)
    {
        $this->queryBuilder = $queryBuilder;
        $this->pdo = $pdo;
        $this->paginator = $paginator;
        $this->queryLogger = $queryLogger;
        $this->relationProcessor = new RelationProcessor($this);
    }

    /**
     * Appends many-to-many grabber to the queue
     * 
     * @param string $alias Alias name
     * @param string $junction Junction table name
     * @param string $column Column name from junction table to be selected
     * @param string $table Slave table name table
     * @param string $pk PK column name in slave table
     * @return \Krystal\Db\Sql\Db
     */
    public function asManyToMany($alias, $junction, $column, $table, $pk)
    {
        $this->relationProcessor->queue(__FUNCTION__, func_get_args());
        return $this;
    }

    /**
     * Appends one-to-one grabber to the queue
     * 
     * @param string $column Column name from the master table to be replaced by alias
     * @param string $alias Alias name for the column name being replaced
     * @param string $table Slave table name
     * @param string $link Linking column name from slave table
     * @return \Krystal\Db\Sql\Db
     */
    public function asOneToOne($column, $alias, $table, $link)
    {
        $this->relationProcessor->queue(__FUNCTION__, func_get_args());
        return $this;
    }

    /**
     * Appends one-to-many grabber to the queue
     * 
     * @param string $table Slave table name
     * @param string $pk Column name which is primary key
     * @param string $alias Alias for result-set
     * @return \Krystal\Db\Sql\Db
     */
    public function asOneToMany($table, $pk, $alias)
    {
        $this->relationProcessor->queue(__FUNCTION__, func_get_args());
        return $this;
    }

    /**
     * Checks whether current driver is a target
     * 
     * @param string $driver
     * @return boolean
     */
    public function isDriver($driver)
    {
        return $this->getDriver() == $driver;
    }

    /**
     * Returns name of current PDO driver
     * 
     * @return string
     */
    public function getDriver()
    {
        return $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
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
     * Initiates a transaction
     * 
     * @return boolean
     */
    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * Checks if inside a transaction
     * 
     * @return boolean
     */
    public function inTransaction()
    {
        return $this->pdo->inTransaction();
    }

    /**
     * Commits a transaction
     * 
     * @return boolean
     */
    public function commit()
    {
        return $this->pdo->commit();
    }

    /**
     * Rolls back a transaction
     * 
     * @throws \PDOException if no transaction is active
     * @return boolean
     */
    public function rollBack()
    {
        return $this->pdo->rollBack();
    }

    /**
     * Prepared raw data before a command is executed
     * 
     * @param array $data
     * @return array
     */
    private function asData(array $data)
    {
        foreach ($data as $key => $value) {
            if ($value instanceof RawSqlFragmentInterface) {
                $data[$key] = $value->getFragment();

            } else {

                $placeholder = $this->toPlaceholder($key);

                $data[$key] = $placeholder;
                $this->bind($placeholder, $value);
            }
        }

        return $data;
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
     * Returns unique placeholder
     * 
     * @return string
     */
    private function getUniqPlaceholder()
    {
        return $this->toPlaceholder(uniqid());
    }

    /**
     * Returns count for pagination
     * This is the implementation of Memento pattern
     * 
     * @param string $column Column to be selected when counting
     * @return integer
     */
    private function getCount($column)
    {
        $alias = 'count';

        // Save initial state
        $original = clone $this->queryBuilder;
        $bindings = $this->bindings;

        // Set guessed query and execute it
        $this->queryBuilder->setQueryString($this->queryBuilder->guessCountQuery($column, $alias));
        $count = $this->query($alias);

        // And finally restore initial state
        $this->queryBuilder = $original;
        $this->bindings = $bindings;

        return $count;
    }

    /**
     * Automatically paginates result-set
     * 
     * @param integer $page
     * @param integer $itemsPerPage
     * @param string $column Column to be selected when counting
     * @throws \RuntimeException If algorithm isn't supported for current driver
     * @return \Krystal\Db\Sql\Db
     */
    public function paginate($page, $itemsPerPage, $column = '1')
    {
        $count = $this->getCount($column);

        if ($this->isDriver('mysql') || $this->isDriver('sqlite')) {
            // Alter paginator's state
            $this->paginator->tweak($count, $itemsPerPage, $page);
            $this->limit($this->paginator->countOffset(), $this->paginator->getItemsPerPage());

        } else {
            throw new RuntimeException('Smart pagination algorithm is currently supported only for MySQL and SQLite');
        }

        return $this;
    }

    /**
     * Binds a value
     * 
     * @param string $placeholder
     * @param string $value
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
     * Appends SHOW KEYS expression
     * 
     * @return \Krystal\Db\Sql\Db
     */
    public function showKeys()
    {
        $this->queryBuilder->showKeys();
        return $this;
    }

    /**
     * Executes raw query
     * 
     * @param string $query
     * @param array $bindings
     * @return \Krystal\Db\Sql\Db
     */
    public function raw($query, array $bindings = array())
    {
        if (!empty($bindings)) {
            foreach ($bindings as $column => $value) {
                $this->bind($column, $value);
            }
        }

        $this->queryBuilder->raw($query);
        return $this;
    }

    /**
     * Returns prepared PDO statement
     * For internal usage only, regarding its public visibility
     * 
     * @throws \RuntimeException If bindings contain nested arrays
     * @return \PDOStatement
     */
    public function getStmt()
    {
        // Make sure there are no nested arrays
        if (ArrayUtils::hasAtLeastOneArrayValue($this->bindings)) {
            throw new RuntimeException('PDO bindings can not contain nested arrays');
        }

        // Build target query before bindings are cleared purely for logging purpose
        $log = str_replace(array_keys($this->bindings), array_values($this->bindings), $this->queryBuilder->getQueryString());

        // Execute it
        $stmt = $this->pdo->prepare($this->queryBuilder->getQueryString());
        $stmt->execute($this->bindings);

        // Log target query
        $this->queryLogger->add($log);

        // Clear the buffer
        $this->clear();

        return $stmt;
    }

    /**
     * Queries for all result-set
     * 
     * @param string $column Optionally can be filtered by a column
     * @param integer $mode Fetch mode. Can be overridden when needed
     * @return mixed
     */
    public function queryAll($column = null, $mode = null)
    {
        if (is_null($mode)) {
            $mode = $this->pdo->getAttribute(PDO::ATTR_DEFAULT_FETCH_MODE);
        }

        $result = array();
        $rows = $this->getStmt()->fetchAll($mode);

        if ($column == null) {
            $result = $rows;
        } else {
            foreach ($rows as $row) {
                if (isset($row[$column])) {
                    $result[] = $row[$column];
                } else {
                    return false;
                }
            }
        }

        if ($this->relationProcessor->hasQueue()) {
            return $this->relationProcessor->process($result);
        } else {
            return $result;
        }
    }

    /**
     * Queries for a single result-set
     * 
     * @param string $column Optionally can be filtered by a column
     * @param integer $mode Fetch mode. Can be overridden when needed
     * @return mixed
     */
    public function query($column = null, $mode = null)
    {
        if (is_null($mode)) {
            $mode = $this->pdo->getAttribute(PDO::ATTR_DEFAULT_FETCH_MODE);
        }

        $result = array();
        $rows = $this->getStmt()->fetch($mode);

        if ($column !== null) {
            if (isset($rows[$column])) {
                $result = $rows[$column];
            } else {
                $result = false;
            }
        } else {
            $result = $rows;
        }

        if ($this->relationProcessor->hasQueue()) {
            $data = $this->relationProcessor->process(array($result));
            return isset($data[0]) ? $data[0] : false;
        }

        // By default
        return $result;
    }

    /**
     * Executes a command
     * 
     * @return boolean
     */
    public function execute()
    {
        // Execute without doiing anything with returned value
        $this->getStmt();
        return true;
    }

    /**
     * Appends RAND() function
     * 
     * @return \Krystal\Db\Sql\Db
     */
    public function now()
    {
        $this->queryBuilder->now();
        return $this;
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
     * Appends ASC
     * 
     * @return \Krystal\Db\Sql
     */
    public function asc()
    {
        $this->queryBuilder->asc();
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
        $this->queryBuilder->increment($table, $column, $step);
        return $this;
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
        $this->queryBuilder->decrement($table, $column, $step);
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
     * Appends GROUP BY statement
     * 
     * @param string|array $target
     * @throws \InvalidArgumentException If $target isn't either a string or an array
     * @return \Krystal\Db\Sql\Db
     */
    public function groupBy($target)
    {
        $this->queryBuilder->groupBy($target);
        return $this;
    }

    /**
     * Appends ORDER BY
     * 
     * @param string|array|\Krystal\Db\Sql\RawSqlFragmentInterface $type
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
        $this->queryBuilder->min($column, $alias);
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
    public function round($column, $decimals)
    {
        $this->queryBuilder->len($column, $decimals);
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
    public function from($table = null)
    {
        $this->queryBuilder->from($table);
        return $this;
    }

    /**
     * Appends a raw comparison
     * 
     * @param string $column
     * @param string $operator
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\Db
     */
    public function compare($column, $operator, $value, $filter = false)
    {
        return $this->constraint(__FUNCTION__, $column, $operator, $value, $filter);
    }

    /**
     * Appends a raw comparison with = operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\Db
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
     * @return \Krystal\Db\Sql\Db
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
     * @return \Krystal\Db\Sql\Db
     */
    public function like($column, $value, $filter = false)
    {
        return $this->compare($column, 'LIKE', $value, $filter);
    }

    /**
     * Appends a raw comparison with > operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\Db
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
     * @return \Krystal\Db\Sql\Db
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
     * @return \Krystal\Db\Sql\Db
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
     * @return \Krystal\Db\Sql\Db
     */
    public function lessThanOrEquals($column, $value, $filter = false)
    {
        return $this->compare($column, '<=', $value, $filter);
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
     * Appends INNER JOIN
     * 
     * @param string $table Right table (second)
     * @param string $a A column from the left table (first)
     * @param string $b A column from the right table (second)
     * @return \Krystal\Db\Sql\Db
     */
    public function innerJoin($table, $a, $b)
    {
        $this->queryBuilder->innerJoin($table, $left, $right);
        return $this;
    }

    /**
     * Appends LEFT JOIN
     * 
     * @param string $table Right table (second)
     * @param string $a A column from the left table (first)
     * @param string $b A column from the right table (second)
     * @return \Krystal\Db\Sql\Db
     */
    public function leftJoin($table, $a, $b)
    {
        $this->queryBuilder->leftJoin($table, $left, $right);
        return $this;
    }

    /**
     * Appends RIGHT JOIN
     * 
     * @param string $table Right table (second)
     * @param string $a A column from the left table (first)
     * @param string $b A column from the right table (second)
     * @return \Krystal\Db\Sql\Db
     */
    public function rightJoin($table, $a, $b)
    {
        $this->queryBuilder->rightJoin($table, $left, $right);
        return $this;
    }

    /**
     * Append FULL OUTER JOIN
     *
     * @param string $table Right table (second)
     * @param string $a A column from the left table (first)
     * @param string $b A column from the right table (second)
     * @return \Krystal\Db\Sql\Db
     */
    public function fullJoin($table, $a, $b)
    {
        $this->queryBuilder->fullJoin($table, $left, $right);
        return $this;
    }

    /**
     * Appends HAVING() clause
     * 
     * @param string $function Aggregate function
     * @param string $column
     * @param string $operator
     * @param string $value
     * @return \Krystal\Db\Sql\Db
     */
    public function having($function, $column, $operator, $value)
    {
        $placeholder = $this->getUniqPlaceholder();

        $this->queryBuilder->having($function, $column, $operator, $placeholder);
        $this->bind($placeholder, $value);

        return $this;
    }

    /**
     * Appends OR WHERE with BETWEEN operator
     *
     * @param string $column
     * @param string $a First value
     * @param string $b Second value
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\Db
     */
    public function orWhereBetween($column, $a, $b, $filter = false)
    {
        return $this->between(__FUNCTION__, $column, $a, $b, $filter);
    }

    /**
     * Appends AND WHERE with BETWEEN operator
     *
     * @param string $column
     * @param string $a First value
     * @param string $b Second value
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\Db
     */
    public function andWhereBetween($column, $a, $b, $filter = false)
    {
        return $this->between(__FUNCTION__, $column, $a, $b, $filter);
    }

    /**
     * Appends WHERE with BETWEEN operator
     * 
     * @param string $column
     * @param string $a First value
     * @param string $b Second value
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\Db
     */
    public function whereBetween($column, $a, $b, $filter = false)
    {
        return $this->between(__FUNCTION__, $column, $a, $b, $filter);
    }

    /**
     * Appends AND WHERE with NOT BETWEEN operator
     * 
     * @param string $column
     * @param string $a First value
     * @param string $b Second value
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\Db
     */
    public function andWhereNotBetween($column, $a, $b, $filter = false)
    {
        return $this->between(__FUNCTION__, $column, $a, $b, $filter);
    }

    /**
     * Appends AND WHERE with NOT BETWEEN operator
     * 
     * @param string $column
     * @param string $a First value
     * @param string $b Second value
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\Db
     */
    public function orWhereNotBetween($column, $a, $b, $filter = false)
    {
        return $this->between(__FUNCTION__, $column, $a, $b, $filter);
    }

    /**
     * Appends WHERE with NOT BETWEEN operator
     *
     * @param string $column
     * @param string $a First value
     * @param string $b Second value
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\Db
     */
    public function whereNotBetween($column, $a, $b, $filter = false)
    {
        return $this->between(__FUNCTION__, $column, $a, $b, $filter);
    }

    /**
     * Adds WHERE with BETWEEN operator 
     * 
     * @param string $method Method to be called from query builder
     * @param string $column
     * @param string $a First value
     * @param string $b Second value
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\Db
     */
    private function between($method, $column, $a, $b, $filter)
    {
        if (!$this->queryBuilder->isFilterable($filter, array($a, $b))) {
            return $this;
        }

        // When doing betweens, unique placeholders come in handy
        $x = $this->getUniqPlaceholder();
        $y = $this->getUniqPlaceholder();

        // Prepare query string
        call_user_func(array($this->queryBuilder, $method), $column, $x, $y, $filter);

        // And finally bind values
        $this->bind($x, $a);
        $this->bind($y, $b);

        return $this;
    }

    /**
     * Adds a constraint to the query
     * 
     * @param string $method
     * @param string $column
     * @param string $operator
     * @param string $value
     * @param boolean $filter Whether to filter by empty value
     * @return \Krystal\Db\Sql\Db
     */
    private function constraint($method, $column, $operator, $value, $filter)
    {
        if (!$this->queryBuilder->isFilterable($filter, $value)) {
            return $this;
        }

        $placeholder = $this->toPlaceholder($column);

        call_user_func(array($this->queryBuilder, $method), $column, $operator, $placeholder);
        $this->bind($placeholder, $value);

        return $this;
    }

    /**
     * Appends WHERE IN (..) expression
     * 
     * @param string $column
     * @param array $values
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\Db
     */
    public function whereIn($column, array $values, $filter = false)
    {
        return $this->whereInValues(__FUNCTION__, $column, $values, $filter);
    }

    /**
     * Internal method to handle WHERE IN methods
     * 
     * @param string $method
     * @param string $column
     * @param array $values
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\Db
     */
    private function whereInValues($method, $column, array $values, $filter)
    {
        if (!$this->queryBuilder->isFilterable($filter, $values)) {
            return $this;
        }

        // Prepare bindings, firstly
        $bindings = array();

        foreach ($values as $value) {
            // Generate unique placeholder
            $placeholder = $this->getUniqPlaceholder();
            // Append to collection
            $bindings[$placeholder] = $value;
        }

        call_user_func(array($this->queryBuilder, $method), $column, array_keys($bindings), $filter);

        // Now bind what we have so far
        foreach ($bindings as $key => $value) {
            $this->bind($key, $value);
        }

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
     * Appends OR WHERE expression
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
     * Appends OR WHERE expression with equality operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter
     * @return \Krystal\Db\Sql\Db
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
     * @return \Krystal\Db\Sql\Db
     */
    public function orWhereNotEquals($column, $value, $filter = false)
    {
        return $this->orWhere($column, '!=', $value, $filter);
    }

    /**
     * Appends OR WHERE with >= operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter
     * @return \Krystal\Db\Sql\Db
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
     * @return \Krystal\Db\Sql\Db
     */
    public function orWhereLessThanOrEquals($column, $value, $filter = false)
    {
        return $this->orWhere($column, '<=', $value, $filter);
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
     * Appends AND WHERE clause with > operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\Db
     */
    public function andWhereGreaterThan($column, $value, $filter = false)
    {
        return $this->andWhere($column, '>', $value, $filter);
    }
    
    /**
     * Appends AND WHERE clause with < operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\Db
     */
    public function andWhereLessThan($column, $value, $filter = false)
    {
        return $this->andWhere($column, '<', $value, $filter);
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
     * @return \Krystal\Db\Sql\Db
     */
    public function orWhereLessThan($column, $value, $filter = false)
    {
        return $this->orWhere($column, '<', $value, $filter);
    }

    /**
     * Appends OR WHERE clause with "greater than" operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\Db
     */
    public function orWhereGreaterThan($column, $value, $filter = false)
    {
        return $this->orWhere($column, '>', $value, $filter);
    }

    /**
     * Appends WHERE with "like" operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\Db
     */
    public function andWhereLike($column, $value, $filter = false)
    {
        $value = $this->queryBuilder->prepareWildcard($value);
        return $this->andWhere($column, 'LIKE', $value, $filter);
    }

    /**
     * Appends OR WHERE LIKE condition
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\Db
     */
    public function orWhereLike($column, $value, $filter = false)
    {
        $value = $this->queryBuilder->prepareWildcard($value);
        return $this->orWhere($column, 'LIKE', $value, $filter);
    }

    /**
     * Appends WHERE LIKE condition
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\Db
     */
    public function whereLike($column, $value, $filter = false)
    {
        $value = $this->queryBuilder->prepareWildcard($value);
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
     * Appends AND WHERE clause with equality operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\Db
     */
    public function andWhereNotEquals($column, $value, $filter = false)
    {
        return $this->andWhere($column, '!=', $value, $filter);
    }

    /**
     * Appends AND WHERE with >= operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter
     * @return \Krystal\Db\Sql\Db
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
     * @return \Krystal\Db\Sql\Db
     */
    public function andWhereEqualsOrLessThan($column, $value, $filter = false)
    {
        return $this->andWhere($column, '<=', $value, $filter);
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
     * @return \Krystal\Db\Sql\Db
     */
    public function union()
    {
        $this->queryBuilder->union();
        return $this;
    }

    /**
     * Appends UNION ALL
     * 
     * @return \Krystal\Db\Sql\Db
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
     * @return \Krystal\Db\Sql\Db
     */
    public function asAlias($alias)
    {
        $this->queryBuilder->asAlias($alias);
        return $this;
    }

    /**
     * Appends raw AND
     * 
     * @return \Krystal\Db\Sql\Db
     */
    public function rawAnd()
    {
        $this->queryBuilder->rawAnd();
        return $this;
    }

    /**
     * Appends raw OR
     * 
     * @return \Krystal\Db\Sql\Db
     */
    public function rawOr()
    {
        $this->queryBuilder->rawOr();
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

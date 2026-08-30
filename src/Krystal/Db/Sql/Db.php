<?php

/**
 * This file is part of the Krystal Framework
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
use Krystal\Text\TextUtils;

/**
 * This is just a bridge between PDO and QueryBuilder, that makes it all work 
 * 
 * @mixin \Krystal\Db\Sql\QueryBuilder
 */
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
     * Dynamically proxy method calls to the underlying QueryBuilder instance.
     * 
     * @param string $method
     * @param array $args
     * @return $this|mixed
     */
    public function __call(string $method, array $args)
    {
        $result = $this->queryBuilder->$method(...$args);

        // If the query builder returns itself, return the Db instance for chaining
        if ($result instanceof QueryBuilder) {
            return $this;
        }

        return $result;
    }

    /**
     * Gets current database version
     * 
     * @return mixed
     */
    public function getVersion()
    {
        return $this->select()
                    ->version()
                    ->queryScalar();
    }

    /**
     * Appends many-to-many grabber to the queue
     * 
     * @param string $alias Alias name
     * @param string $junction Junction table name
     * @param string $column Column name from junction table to be selected
     * @param string $table Slave table name table
     * @param string $pk PK column name in slave table
     * @param mixed $columns Columns to be selected in slave table
     * @return \Krystal\Db\Sql\Db
     */
    public function asManyToMany($alias, $junction, $column, $table, $pk, $columns = '*')
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
            } elseif ($value instanceof RawBindingInterface) {
                $data[$key] = $value->getTarget();
            } else {
                $placeholder = $this->getUniqPlaceholder();

                $data[$key] = $placeholder;
                $this->bind($placeholder, $value);
            }
        }

        return $data;
    }

    /**
     * Creates unique placeholder
     * 
     * @param string $key
     * @return string
     */
    private function createUniqPlaceholder($key)
    {
        if ($key instanceof RawSqlFragment) {
            $placeholder = $key->getFragment();
        } else if ($key instanceof RawBindingInterface) {
            $placeholder = $key->getTarget();
        } else {
            // Create unique placeholder
            $placeholder = $this->getUniqPlaceholder();

            // Bind to the global stack
            $this->bind($placeholder, $key);
        }

        return $placeholder;
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
        $id = TextUtils::uniqueString();
        return $this->toPlaceholder($id);
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
     * Fetch all tables
     * 
     * @return array
     */
    public function fetchAllTables()
    {
        $tables = array();
        $result = $this->pdo->query('SHOW TABLES')->fetchAll();

        foreach ($result as $index => $array) {
            // Extract a value - we don't care about a key
            $data = array_values($array);
            // Its ready not, just append it
            $tables[] = $data[0];
        }

        return $tables;
    }

    /**
     * Dump tables into SQL string
     * 
     * @param array $tables If empty current tables will be taken into account
     * @return string
     */
    public function dump(array $tables = array())
    {
        $result = null;

        if (empty($tables)) {
            $tables = $this->fetchAllTables();
        }

        // Building logic
        foreach ($tables as $table) {
            // Main SELECT query
            $select = $this->queryBuilder->clear()
                                         ->select('*')
                                         ->from($table)
                                         ->getQueryString();

            $stmt = $this->pdo->query($select);
            $fieldCount = $stmt->columnCount();

            // Append additional drop state
            $result .= $this->queryBuilder->clear()
                                          ->dropTable($table)
                                          ->getQueryString();;

            // Show how this table was created
            $createResult = $this->pdo->query(sprintf('SHOW CREATE TABLE %s', $table))->fetch();
            $result .=  "\n\n" . $createResult['Create Table'] . ";\n\n";

            // Start main loop
            for ($i = 0; $i < $fieldCount; $i++) {
                // Loop to generate INSERT statements
                while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                    $values = array();

                    // Extra values and push them to $values array
                    for ($j = 0; $j < $fieldCount; $j++) {
                        // We need to ensure all quotes are properly escaped
                        $row[$j] = addslashes($row[$j]);

                        // Ensure its correctly escaped
                        $row[$j] = str_replace("\n", "\\n", $row[$j]);
                        $row[$j] = sprintf('"%s"', $row[$j]);

                        // Push the value
                        array_push($values, $row[$j]);
                    }

                    // Generate short INSERT statement
                    $result .= $this->queryBuilder->clear()
                                                  ->insertShort($table, $values)
                                                  ->getQueryString();

                    $result .= "\n";

                    // Free memory for next iteration
                    unset($vals);
                }
            }

            $result .= "\n";
        }

        return $result;
    }

    /**
     * Returns a word with wildcard. Can be used for LIKE constraints
     * 
     * @param string $target
     * @param string $type
     * @throws \RuntimeException if unknown type supplied
     * @return string
     */
    public function getWithWildcart($target, $type = self::LIKE_RAW)
    {
        return $this->queryBuilder->getWithWildcart($target, $type);
    }

    /**
     * Paginates a result-set without automatic query guessing
     * 
     * @param integer $count
     * @param integer $page Current page
     * @param integer $itemsPerPage Items per page to be shown
     * @return \Krystal\Db\Sql\Db
     */
    public function paginateRaw($count, $page, $itemsPerPage)
    {
        $this->paginator->tweak((int) $count, (int) $itemsPerPage, (int) $page);
        $this->limit($this->paginator->countOffset(), $this->paginator->getItemsPerPage());

        return $this;
    }

    /**
     * Automatically paginates result-set
     * 
     * @param integer $page Current page
     * @param integer $itemsPerPage Items per page to be shown
     * @param string $column Column to be selected when counting
     * @throws \RuntimeException If algorithm isn't supported for current driver
     * @return \Krystal\Db\Sql\Db
     */
    public function paginate($page, $itemsPerPage, $column = '1')
    {
        // Extra checking if values are number before querying a database
        if (is_numeric($page) && is_numeric($itemsPerPage)) {
            $count = $this->getCount($column);

            if ($this->isDriver('mysql') || $this->isDriver('sqlite')) {
                // Alter paginator's state
                $this->paginateRaw($count, $page, $itemsPerPage);
            } else {
                throw new RuntimeException('Smart pagination algorithm is currently supported only for MySQL and SQLite');
            }
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
     * Gets primary out of a table
     * 
     * @param string $table
     * @return string|boolean False if no primary key available
     */
    public function getPrimaryKey($table)
    {
        $db = $this->showKeys()->from($table)
                               ->whereEquals('Key_name', new RawBinding('PRIMARY'));

        return $db->query('Column_name');
    }

    /**
     * Appends raw SQL fragment
     * 
     * @param string $fragment
     * @return \Krystal\Db\Sql\Db
     */
    public function append($fragment)
    {
        $this->queryBuilder->append($fragment);
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
     * Queries for a single result-set returning a value of a first column
     * 
     * @param integer $mode Fetch mode. Can be overridden when needed
     * @return string|boolean
     */
    public function queryScalar($mode = null)
    {
        $result = $this->query(null, $mode);

        if (is_array($result)) {
            // Filter by values
            $result = array_values($result);
            return isset($result[0]) ? $result[0] : false;
        }

        return false;
    }

    /**
     * Executes a command
     * 
     * @param string $rowCount Whether to return a number of affected rows
     * @return boolean|integer
     */
    public function execute($rowCount = false)
    {
        $stmt = $this->getStmt();

        if ($rowCount === true) {
            return $stmt->rowCount();
        } else {
            return true;
        }
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
     * Appends raw $column IN (..) fragment
     * 
     * @param string $column
     * @param array $values
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function in($column, array $values, $filter = false)
    {
        return $this->whereInValues(__FUNCTION__, $column, $values, $filter);
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
     * Appends a raw comparison with NOT LIKE operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\Db
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
        if ($amount != null) {
            $amount = (int) $amount;
        }

        $this->queryBuilder->limit((int) $offset, $amount);
        return $this;
    }

    /**
     * Generates SET key = value fragment
     * 
     * @param array $values
     * @return \Krystal\Db\Sql\Db
     */
    public function set(array $values)
    {
        $this->queryBuilder->set($this->asData($values));
        return $this;
    }

    /**
     * Updates a table
     * 
     * @param string $table
     * @param array $data Optional data to be updated
     * @return \Krystal\Db\Sql\Db
     */
    public function update($table, array $data = array())
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
     * Builds and appends INSERT statement without explicit column names
     * 
     * @param string $table
     * @param array $values Values to be inseted in columns
     * @param boolean $ignore Whether to ignore when PK collisions occur
     * @throws \LogicException if $values array is empty
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function insertShort($table, array $values, $ignore = false)
    {
        $collection = array();

        foreach ($values as $value) {
            $collection[] = $this->createUniqPlaceholder($value);
        }

        $this->queryBuilder->insertShort($table, $collection, $ignore);
        return $this;
    }

    /**
     * Generate INSERT query for many records
     * 
     * @param string $table
     * @param array $columns
     * @param array $values
     * @return \Krystal\Db\Sql\Db
     */
    public function insertMany($table, array $columns, array $values)
    {
        $collection = array();

        foreach ($values as $index => $data) {
            foreach ($data as $key) {
                $collection[$index][] = $this->createUniqPlaceholder($key);
            }
        }

        $this->queryBuilder->insertMany($table, $columns, $collection);
        return $this;
    }

    /**
     * Appends special INSERT statement for junction table
     * 
     * @param string $table Junction table name
     * @param array $columns
     * @param string $master Master value
     * @param array $slaves Slave keys
     * @throws \LogicException If the count of columns isn't 2
     * @return \Krystal\Db\Sql\Db
     */
    public function insertIntoJunction($table, array $columns, $master, array $slaves)
    {
        $collection = array();

        foreach ($slaves as $key) {
            $collection[] = $this->createUniqPlaceholder($key);
        }

        $this->queryBuilder->insertIntoJunction($table, $columns, $master, $collection);
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

        if ($a instanceof RawSqlFragmentInterface) {
            $x = $a->getFragment();
        }

        if ($b instanceof RawSqlFragmentInterface) {
            $y = $b->getFragment();
        }

        // When doing betweens, unique placeholders come in handy
        if (!isset($x)) {
            $x = $this->getUniqPlaceholder();
        }

        if (!isset($y)) {
            $y = $this->getUniqPlaceholder();
        }

        // Prepare query string
        call_user_func(array($this->queryBuilder, $method), $column, $x, $y, $filter);

        // And finally bind values
        if (!($a instanceof RawSqlFragmentInterface)) {
            $this->bind($x, $a);
        }

        if (!($b instanceof RawSqlFragmentInterface)) {
            $this->bind($y, $b);
        }

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

        call_user_func(array($this->queryBuilder, $method), $column, $operator, $this->createUniqPlaceholder($value));
        return $this;
    }

    /**
     * Appends WHERE column IN (..) expression
     * 
     * @param string $column
     * @param array|\Krystal\Db\Sql\RawBindingInterface|\Krystal\Db\Sql\RawSqlFragmentInterface $values
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\Db
     */
    public function whereIn($column, $values, $filter = false)
    {
        return $this->whereInValues(__FUNCTION__, $column, $values, $filter);
    }

    /**
     * Appends WHERE column NOT IN (..) expression
     * 
     * @param string $column
     * @param array|\Krystal\Db\Sql\RawBindingInterface|\Krystal\Db\Sql\RawSqlFragmentInterface $values
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\Db
     */
    public function whereNotIn($column, $values, $filter = false)
    {
        return $this->whereInValues(__FUNCTION__, $column, $values, $filter);
    }

    /**
     * Appends AND column IN (..) expression
     * 
     * @param string $column
     * @param array|\Krystal\Db\Sql\RawBindingInterface|\Krystal\Db\Sql\RawSqlFragmentInterface $values
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\Db
     */
    public function andWhereIn($column, $values, $filter = false)
    {
        return $this->whereInValues(__FUNCTION__, $column, $values, $filter);
    }

    /**
     * Appends AND column NOT IN (..) expression
     * 
     * @param string $column
     * @param array|\Krystal\Db\Sql\RawBindingInterface|\Krystal\Db\Sql\RawSqlFragmentInterface $values
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\Db
     */
    public function andWhereNotIn($column, $values, $filter = false)
    {
        return $this->whereInValues(__FUNCTION__, $column, $values, $filter);
    }

    /**
     * Appends OR column IN (..) expression
     * 
     * @param string $column
     * @param array|\Krystal\Db\Sql\RawBindingInterface|\Krystal\Db\Sql\RawSqlFragmentInterface $values
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\Db
     */
    public function orWhereIn($column, $values, $filter = false)
    {
        return $this->whereInValues(__FUNCTION__, $column, $values, $filter);
    }

    /**
     * Appends OR column NOT IN (..) expression
     * 
     * @param string $column
     * @param array|\Krystal\Db\Sql\RawBindingInterface|\Krystal\Db\Sql\RawSqlFragmentInterface $values
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\Db
     */
    public function orWhereNotIn($column, $values, $filter = false)
    {
        return $this->whereInValues(__FUNCTION__, $column, $values, $filter);
    }

    /**
     * Internal method to handle WHERE IN methods
     * 
     * @param string $method
     * @param string $column
     * @param array|\Krystal\Db\Sql\RawBindingInterface|\Krystal\Db\Sql\RawSqlFragmentInterface $values
     * @param boolean $filter Whether to rely on filter
     * @return \Krystal\Db\Sql\Db
     */
    private function whereInValues($method, $column, $values, $filter)
    {
        if (!$this->queryBuilder->isFilterable($filter, $values)) {
            return $this;
        }

        if ($values instanceof RawBindingInterface) {
            call_user_func(array($this->queryBuilder, $method), $column, $values->getTarget(), $filter);
        } elseif ($values instanceof RawSqlFragmentInterface) {
            call_user_func(array($this->queryBuilder, $method), $column, $values, $filter);
        } else {
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
        return $this->andWhere($column, 'LIKE', $value, $filter);
    }

    /**
     * Appends WHERE with "NOT LIKE" operator
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\Db
     */
    public function andWhereNotLike($column, $value, $filter = false)
    {
        return $this->andWhere($column, 'NOT LIKE', $value, $filter);
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
        return $this->orWhere($column, 'LIKE', $value, $filter);
    }

    /**
     * Appends OR WHERE NOT LIKE condition
     * 
     * @param string $column
     * @param string $value
     * @param boolean $filter Whether to filter by value
     * @return \Krystal\Db\Sql\Db
     */
    public function orWhereNotLike($column, $value, $filter = false)
    {
        return $this->orWhere($column, 'NOT LIKE', $value, $filter);
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
}

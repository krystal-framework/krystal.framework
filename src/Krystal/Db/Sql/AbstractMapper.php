<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Sql;

use Krystal\Paginate\PaginatorInterface;
use Krystal\Stdlib\ArrayUtils;
use Krystal\Text\Math;
use LogicException;
use RuntimeException;

abstract class AbstractMapper
{
    /**
     * Prepare paginator's instance
     * 
     * @var \Krystal\Paginate\PaginatorInterface
     */
    protected $paginator;

    /**
     * Database handler
     * 
     * @var \Krystal\Db\Sql\Db
     */
    protected $db;

    /**
     * Optional table prefix
     * 
     * @var string
     */
    protected static $prefix;

    /* Default convention for column names in junction tables */
    const PARAM_JUNCTION_MASTER_COLUMN = 'master_id';
    const PARAM_JUNCTION_SLAVE_COLUMN = 'slave_id';

    /**
     * State initialization
     * 
     * @param \Krystal\Db\Sql\Db Database service object
     * @param \Krystal\Paginate\PaginatorInterface $paginator
     * @param string $prefix Optional table prefix
     * @return void
     */
    public function __construct(Db $db, PaginatorInterface $paginator, $prefix = null)
    {
        $this->db = $db;
        $this->setPaginator($paginator);
        static::$prefix = $prefix;
    }

    /**
     * Returns a column wrapping in \RawSqlFragment instance
     * 
     * @param string $column Column name
     * @param string $table Table name. By default getTableName() is called
     * @return \Krystal\Db\Sql\Db\RawSqlFragment
     */
    public static function getRawColumn($column, $table = null)
    {
        return new RawSqlFragment(self::getFullColumnName($column, $table));
    }

    /**
     * An alias for self::getFullColumnName()
     * 
     * @param string $column Column name
     * @param string $table Table name. By default getTableName() is called
     * @throws \LogicException if the getTableName() isn't defined in descending class
     * @return string
     */
    public static function column($column, $table = null)
    {
        return self::getFullColumnName($column, $table);
    }

    /**
     * Returns full column name prepending table name
     * 
     * @param string $column Column name
     * @param string $table Table name. By default getTableName() is called
     * @throws \LogicException if the getTableName() isn't defined in descending class
     * @return string
     */
    public static function getFullColumnName($column, $table = null)
    {
        // Get target table name
        if ($table === null) {
            // Make sure, the getTableName() is defined in calling class
            if (method_exists(get_called_class(), 'getTableName')) {
                $table = static::getTableName();
            } else {
                throw new LogicException(
                    sprintf('The method getTableName() is not declared in %s, therefore a full column name cannot be generated', get_called_class())
                );
            }
        }

        return sprintf('%s.%s', $table, $column);
    }

    /**
     * Returns table name if not null. Otherwise returns mapper's table name
     * 
     * @param string $table
     * @return string
     */
    private function getTable($table)
    {
        if (is_null($table)) {
            $table = static::getTableName();
        }

        return $table;
    }

    /**
     * Returns table name with a prefix
     * 
     * @param string $table
     * @return string
     */
    protected static function getWithPrefix($table)
    {
        $prefix = static::$prefix;

        if (is_null($prefix) || empty($prefix)) {
            // If prefix is null, then no need to prepend a redundant _
            return $table;
        }

        return sprintf('%s_%s', $prefix, $table);
    }

    /**
     * Executes raw SQL from a file
     * 
     * @param string $file
     * @throws \RuntimeException On reading failure
     * @return boolean
     */
    final protected function executeSqlFromFile($file)
    {
        if (is_file($file)) {
            return $this->executeSqlFromString(file_get_contents($file));
        } else {
            throw new RuntimeException(sprintf('Can not read file at "%s"', $file));
        }
    }

    /**
     * Executes raw SQL from a string
     * 
     * @param string $sql
     * @return integer Number of affected rows
     */
    final protected function executeSqlFromString($sql)
    {
        return $this->db->getPdo()->exec($sql);
    }

    /**
     * Checks whether shortcut data
     * 
     * @return void
     */
    final protected function validateShortcutData()
    {
        if (!method_exists($this, 'getPk') || !method_exists($this, 'getTableName')) {
            throw new LogicException('To use shortcut methods you have to implement one protected getPk() and one public static getTableName() methods');
        }
    }

    /**
     * Determines whether column value already exists
     * 
     * @param string $column Column name
     * @param string $value Column value to be checked
     * @return boolean
     */
    final protected function valueExists($column, $value)
    {
        $this->validateShortcutData();

        return (bool) $this->db->select()
                               ->count($column)
                               ->from(static::getTableName())
                               ->whereEquals($column, $value)
                               ->queryScalar();
    }

    /**
     * Checks whether there's at least one row matching constraints
     * 
     * @param array $constraints
     * @return boolean
     */
    final protected function valuesExist(array $constraints)
    {
        $this->validateShortcutData();

        $db = $this->db->select()
                       ->count($this->getPk())
                       ->from(static::getTableName())
                       ->whereEquals('1', '1');

        foreach ($constraints as $column => $value) {
            $db->andWhereEquals($column, $value);
        }

        return (bool) $db->queryScalar();
    }

    /**
     * Deletes a row by associated PK's value
     * 
     * @param string $pk PK's value
     * @return boolean
     */
    final public function deleteByPk($pk)
    {
        $this->validateShortcutData();
        return $this->deleteByColumn($this->getPk(), $pk);
    }
    
    /**
     * Deletes rows by their associated primary keys
     * 
     * @param array $ids
     * @return boolean
     */
    final public function deleteByPks(array $ids)
    {
        foreach ($ids as $id) {
            if (!$this->deleteByPk($id)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Finds a row by associated PK's value
     * 
     * @param string $pk PK's value
     * @return array
     */
    final public function findByPk($pk)
    {
        $this->validateShortcutData();
        return $this->fetchByColumn($this->getPk(), $pk);
    }

    /**
     * Returns column's value by provided PK
     * 
     * @param string $id PK's value
     * @param string $column
     * @return boolean
     */
    final public function findColumnByPk($id, $column)
    {
        $this->validateShortcutData();
        return $this->fetchOneColumn($column, $this->getPk(), $id);
    }

    /**
     * Returns last primary key
     * 
     * @param string $table Optional table name override
     * @return integer
     */
    final public function getLastPk($table = null)
    {
        if ($table === null) {
            $this->validateShortcutData();
            $table = static::getTableName();
        }

        return $this->db->select()
                        ->max($this->getPk())
                        ->from($table)
                        ->queryScalar();
    }

    /**
     * Synchronizes a junction table
     * 
     * @param string $table Junction table name
     * @param string $masterColumn Master column name
     * @param string $masterValue Master value (shared for slaves)
     * @param string $slaveColumn Slave column name
     * @param array $slaves A collection of slave values
     * @return boolean
     */
    final public function syncWithJunction($table, $masterValue, array $slaves, $masterColumn = self::PARAM_JUNCTION_MASTER_COLUMN, $slaveColumn = self::PARAM_JUNCTION_SLAVE_COLUMN)
    {
        // Remove previous ones
        $this->removeFromJunction($table, $masterValue, $masterColumn);

        // And insert new ones
        $this->insertIntoJunction($table, $masterValue, $slaves, $masterColumn, $slaveColumn);

        return true;
    }

    /**
     * Removes all records from junction table associated with master's key
     * 
     * @param string $table Junction table name
     * @param string|array $masterValue Master value (shared for slaves)
     * @param string $masterColumn Master column name
     * @return boolean
     */
    final public function removeFromJunction($table, $masterValue, $masterColumn = self::PARAM_JUNCTION_MASTER_COLUMN)
    {
        // Support for multiple removal
        if (!is_array($masterValue)) {
            $masterValue = array($masterValue);
        }

        return $this->db->delete()
                        ->from($table)
                        ->whereIn($masterColumn, $masterValue)
                        ->execute();
    }

    /**
     * Inserts a record into junction table
     * 
     * @param string $table Junction table name
     * @param string $masterValue
     * @param array $slaves
     * @param string $masterColumn Master column name
     * @param string $slaveColumn Slave column name
     * @return boolean
     */
    final public function insertIntoJunction($table, $masterValue, array $slaves, $masterColumn = self::PARAM_JUNCTION_MASTER_COLUMN, $slaveColumn = self::PARAM_JUNCTION_SLAVE_COLUMN)
    {
        // Avoid executing empty query
        if (!empty($slaves)) {
            return $this->db->insertIntoJunction($table, array($masterColumn, $slaveColumn), $masterValue, $slaves)
                            ->execute();
        } else {
            return false;
        }
    }

    /**
     * Fetches master values associated with a slave in junction table
     * 
     * @param string $table Junction table name
     * @param string $value Slave value
     * @param string $masterColumn Master column name
     * @param string $slaveColumn Slave column name
     * @return array
     */
    final public function getMasterIdsFromJunction($table, $value, $masterColumn = self::PARAM_JUNCTION_MASTER_COLUMN, $slaveColumn = self::PARAM_JUNCTION_SLAVE_COLUMN)
    {
        return $this->getIdsFromJunction($table, $masterColumn, $slaveColumn, $value);
    }

    /**
     * Fetches master values associated with a slave in junction table
     * 
     * @param string $table Junction table name
     * @param string $value Master value
     * @param string $masterColumn Master column name
     * @param string $slaveColumn Slave column name
     * @return array
     */
    final public function getSlaveIdsFromJunction($table, $value, $masterColumn = self::PARAM_JUNCTION_MASTER_COLUMN, $slaveColumn = self::PARAM_JUNCTION_SLAVE_COLUMN)
    {
        return $this->getIdsFromJunction($table, $slaveColumn, $masterColumn, $value);
    }

    /**
     * Fetches values from junction table
     * 
     * @param string $table Junction table name
     * @param string $column Column to be selected
     * @param string $key
     * @param string $value
     * @return array
     */
    private function getIdsFromJunction($table, $column, $key, $value)
    {
        return $this->db->select($column)
                        ->from($table)
                        ->whereEquals($key, $value)
                        ->queryAll($column);
    }

    /**
     * Counts the sum of a column by district id
     * 
     * @param string $columns
     * @param array $averages
     * @param array $constraints
     * @param integer $precision Float precision
     * @return string
     */
    final protected function getColumnSumWithAverages(array $columns, array $averages, array $constraints, $precision = 2)
    {
        $db = $this->db->select();

        foreach ($columns as $column) {
            $db->sum($column, $column);
        }

        foreach ($averages as $average) {
            $db->avg($average, $average);
        }

        $db->from(static::getTableName());

        if (!empty($constraints)) {
            // Iteration counter
            $iteration = 0;

            foreach ($constraints as $key => $value) {
                if ($iteration === 0) {
                    $db->whereEquals($key, $value);
                } else {
                    $db->andWhereEquals($key, $value);
                }

                $iteration++;
            }
        }

        $data = $db->queryAll();

        if (isset($data[0])) {
            return Math::roundCollection($data[0], $precision);
        } else {
            // No results
            return array();
        }
    }

    /**
     * Returns a sum of columns
     * 
     * @param array $columns
     * @param array $constraints
     * @param integer $precision Float precision
     * @return array
     */
    final public function getColumsSum(array $columns, array $constraints = array(), $precision = 2)
    {
        $db = $this->db->select();

        foreach ($columns as $column) {
            $db->sum($column, $column);
        }

        $db->from(static::getTableName());

        if (!empty($constraints)) {
            // Iteration counter
            $iteration = 0;

            foreach ($constraints as $key => $value) {
                if ($iteration === 0) {
                    $db->whereEquals($key, $value);
                } else {
                    $db->andWhereEquals($key, $value);
                }

                $iteration++;
            }
        }

        $data = $db->queryAll();

        if (isset($data[0])) {
            return Math::roundCollection($data[0], $precision);
        } else {
            // No results
            return array();
        }
    }

    /**
     * Persists a row
     * 
     * @param array $data
     * @param array $fillable Optional fillable protection
     * @param boolean $set Whether to return a set or not
     * @throws \LogicException if failed on keys existence validation
     * @return array|boolean
     */
    private function persistRecord(array $data, array $fillable, $set)
    {
        if (!empty($fillable) && !ArrayUtils::keysExist($data, $fillable)) {
            throw new LogicException('Can not persist the entity due to fillable protection. Make sure all fillable keys exist in the entity');
        }

        $this->validateShortcutData();

        if (isset($data[$this->getPk()]) && $data[$this->getPk()]) {
            $result = $this->db->update(static::getTableName(), $data)
                               ->whereEquals($this->getPk(), $data[$this->getPk()])
                               ->execute();

            return $set === true ? $data : $result;

        } else {
            // Do not insert primary key if present
            if (array_key_exists($this->getPk(), $data)) {
                unset($data[$this->getPk()]);
            }

            // Insert a new row without PK
            $result = $this->db->insert(static::getTableName(), $data)
                               ->execute();

            if ($set === true) {
                // Append a PK in result-set now
                $data[$this->getPk()] = $this->getMaxId();
                return $data;
            } else {
                return $result;
            }
        }
    }

    /**
     * Persists a row
     * 
     * @param array $data
     * @param array $fillable Optional fillable protection
     * @throws \LogicException if failed on keys existence validation
     * @return array
     */
    final public function persistRow(array $data, array $fillable = array())
    {
        return $this->persistRecord($data, $fillable, true);
    }

    /**
     * Inserts or updates a record
     * 
     * @param array $data
     * @param array $fillable Optional fillable protection
     * @throws \LogicException if failed on keys existence validation
     * @return boolean
     */
    final public function persist(array $data, array $fillable = array())
    {
        return $this->persistRecord($data, $fillable, false);
    }

    /**
     * Inserts or updates many records at once
     * 
     * @param array $collection
     * @param array $fillable Optional fillable protection
     * @throws \LogicException if failed on keys existence validation
     * @return boolean
     */
    final public function persistMany(array $collection, array $fillable = array())
    {
        foreach ($collection as $item) {
            $this->persist($item, $fillable);
        }

        return true;
    }

    /**
     * Updates column's value by a primary key
     * 
     * @param string $pk
     * @param string $column
     * @param string $value
     * @return boolean
     */
    final public function updateColumnByPk($pk, $column, $value)
    {
        return $this->updateColumnsByPk($pk, array($column => $value));
    }

    /**
     * Update multiple columns at once
     * 
     * @param array $data
     * @param array $allowedColumns Optional protection for columns to be updated (purely for protection purposes)
     * @return boolean
     */
    final public function updateColumns(array $data, array $allowedColumns = array())
    {
        foreach ($data as $column => $values) {
            foreach ($values as $id => $value) {
                // Protection. Update only defined columns
                if (!empty($allowedColumns) && !in_array($column, $allowedColumns)) {
                    continue;
                }

                $this->updateColumnByPk($id, $column, $value);
            }
        }

        return true;
    }

    /**
     * Updates column values by a primary key
     * 
     * @param string $pk
     * @param array $data Columns and their values to be updated
     * @return boolean
     */
    final public function updateColumnsByPk($pk, $data)
    {
        $this->validateShortcutData();

        return $this->db->update(static::getTableName(), $data)
                        ->whereEquals($this->getPk(), $pk)
                        ->execute();
    }

    /**
     * Checks whether PK value exists
     * 
     * @param string $value PK's value
     * @return boolean
     */
    final public function isPrimaryKeyValue($value)
    {
        $column = $this->getPk();

        return (bool) $this->db->select($column)
                               ->from(static::getTableName())
                               ->whereEquals($column, $value)
                               ->query($column);
    }

    /**
     * Fetches one column
     * 
     * @param string $column
     * @param string $key
     * @param string $value
     * @return array
     */
    final public function fetchOneColumn($column, $key, $value)
    {
        $this->validateShortcutData();

        return $this->db->select($column)
                        ->from(static::getTableName())
                        ->whereEquals($key, $value)
                        ->query($column);
    }

    /**
     * Fetches columns. Unlike fetchOneColumn() it queries for all
     * 
     * @param string $column
     * @param string $key
     * @param string $value
     * @return array
     */
    final public function fetchColumns($column, $key, $value)
    {
        $this->validateShortcutData();

        return $this->db->select($column)
                        ->from(static::getTableName())
                        ->whereEquals($key, $value)
                        ->queryAll($column);
    }

    /**
     * Finds all records filtering by column's value
     * 
     * @param string $column
     * @param string $value
     * @return array
     */
    final public function findAllByColumn($column, $value)
    {
        return $this->fetchAllByColumn($column, $value);
    }

    /**
     * Fetches all row data by column's value
     * 
     * @param string $column
     * @param string $value
     * @param mixed $select
     * @return array
     */
    final public function fetchAllByColumn($column, $value, $select = '*')
    {
        $this->validateShortcutData();

        return $this->db->select($select)
                        ->from(static::getTableName())
                        ->whereEquals($column, $value)
                        ->queryAll();
    }

    /**
     * Fetches single row a column
     * 
     * @param string $column The name of PK column
     * @param string $value The value of PK
     * @param mixed $select Data to be selected. By default all columns are selected
     * @return array
     */
    final public function fetchByColumn($column, $value, $select = '*')
    {
        $this->validateShortcutData();

        return $this->db->select($select)
                        ->from(static::getTableName())
                        ->whereEquals($column, $value)
                        ->query();
    }

    /**
     * Deletes a row by its associated PK
     * 
     * @param string PK's column
     * @param string PK's value
     * @return boolean
     */
    final public function deleteByColumn($column, $value)
    {
        $this->validateShortcutData();

        $db = $this->db->delete()
                       ->from(static::getTableName())
                       ->whereEquals($column, $value);

        return (bool) $db->execute(true);
    }

    /**
     * Counts by provided column's value
     * 
     * @param string $column
     * @param string $value
     * @param string $field Field to be counted. By default the value of PK is taken
     * @return integer
     */
    final public function countByColumn($column, $value, $field = null)
    {
        $this->validateShortcutData();
        $alias = 'count';

        if ($field === null) {
            $field = $this->getPk();
        }

        return (int) $this->db->select()
                            ->count($field, $alias)
                            ->from(static::getTableName())
                            ->whereEquals($column, $value)
                            ->query($alias);
    }

    /**
     * Increments column's value by associated PK's value
     * 
     * @param string $pk PK's value
     * @param string $column Target column
     * @param integer $step
     * @param string $table Optionally current table can be overridden
     * @return boolean
     */
    final public function incrementColumnByPk($pk, $column, $step = 1, $table = null)
    {
        return $this->db->increment($this->getTable($table), $column, $step)
                        ->whereEquals($this->getPk(), $pk)
                        ->execute();
    }

    /**
     * Decrements column's value by associated PK's value
     * 
     * @param string $pk PK's value
     * @param string $column Target column
     * @param integer $step
     * @param string $table Optionally current table can be overridden
     * @return boolean
     */
    final public function decrementColumnByPk($pk, $column, $step = 1, $table = null)
    {
        return $this->db->decrement($this->getTable($table), $column, $step)
                        ->whereEquals($this->getPk(), $pk)
                        ->execute();
    }

    /**
     * Drops a table
     * 
     * @param string $table Optionally current table can be overridden
     * @return boolean
     */
    final public function dropTable($table = null)
    {
        $table = $this->getTable($table);

        return $this->db->dropTable($table, true)
                        ->execute();
    }

    /**
     * Drop several tables at once
     * 
     * @param array $tables
     * @param boolean $fkChecks Whether to enabled Foreign Key checks
     * @return boolean
     */
    final public function dropTables(array $tables, $fkChecks = false)
    {
        // Whether FK checks are enabled
        if ($fkChecks == false) {
            $this->db->raw('SET FOREIGN_KEY_CHECKS=0')
                     ->execute();
        }

        foreach ($tables as $table) {
            if (!$this->dropTable($table)) {
                return false;
            }
        }

        // Whether FK checks are enabled
        if ($fkChecks == false) {
            $this->db->raw('SET FOREIGN_KEY_CHECKS=1')
                     ->execute();
        }

        return true;
    }

    /**
     * Adds a column
     * 
     * @param string $column Column name
     * @param string $type Column type
     * @param string $table Table name. By default it uses mapper's table
     * @return boolean
     */
    public function addColumn($column, $type, $table = null)
    {
        return $this->db->alterTable($this->getTable($table))
                        ->addColumn($column, $type)
                        ->execute();
    }

    /**
     * Drops a column
     * 
     * @param string $column Column name
     * @param string $table Table name. By default it uses mapper's table
     * @return boolean
     */
    public function dropColumn($column, $table = null)
    {
        return $this->db->alterTable($this->getTable($table))
                        ->dropColumn($column)
                        ->execute();
    }

    /**
     * Renames a column
     * 
     * @param string $old Old name
     * @param string $new New name
     * @param string $table Table name. By default it uses mapper's table
     * @return boolean
     */
    public function renameColumn($old, $new, $table = null)
    {
        return $this->db->alterTable($this->getTable($table))
                        ->renameColumn($old, $new)
                        ->execute();
    }

    /**
     * Alters a column
     * 
     * @param string $column Column name
     * @param string $type Column type
     * @param string $table Table name. By default it uses mapper's table
     * @return boolean
     */
    public function alterColumn($column, $type, $table = null)
    {
        return $this->db->alterTable($this->getTable($table))
                        ->alterColumn($column, $type)
                        ->execute();
    }

    /**
     * Creates an INDEX
     * 
     * @param string $name Index name
     * @param string|array $target Column name or collection of ones
     * @param boolean $unique Whether it should be unique or not
     * @param string $table Table name. By default it uses mapper's table
     * @return boolean
     */
    public function createIndex($name, $target, $unique = false, $table = null)
    {
        return $this->db->createIndex($table, $name, $target, $unique)
                        ->execute();
    }

    /**
     * Drops an INDEX
     * 
     * @param string $name Index name
     * @param string $table Table name. By default it uses mapper's table
     * @return boolean
     */
    public function dropIndex($name, $table = null)
    {
        return $this->db->dropIndex($table, $name)
                        ->execute();
    }
    
    /**
     * Adds a primary key
     * 
     * @param string $name PK's name
     * @param string|array $target Column name or collection of ones
     * @param string $table Table name. By default it uses mapper's table
     * @return boolean
     */
    public function addPrimaryKey($name, $target, $table = null)
    {
        return $this->db->alterTable($this->getTable($table))
                        ->addConstraint($name)
                        ->primaryKey($target)
                        ->execute();
    }

    /**
     * Drops a primary key
     * 
     * @param string $name PK's name
     * @param string $table Table name. By default it uses mapper's table
     * @return boolean
     */
    public function dropPrimaryKey($name, $table = null)
    {
        return $this->db->alterTable($this->getTable($table))
                        ->dropConstraint($name)
                        ->execute();
    }

    /**
     * Returns maximal id
     * 
     * @return integer
     */
    public function getMaxId()
    {
        $column = $this->getPk();

        return $this->db->select($column)
                        ->from(static::getTableName())
                        ->orderBy($column)
                        ->desc()
                        ->limit(1)
                        ->query($column);
    }

    /**
     * Returns last id
     * 
     * @return integer
     */
    public function getLastId()
    {
        return $this->db->getPdo()
                        ->lastInsertId();
    }

    /**
     * Sets paginator's instance
     * 
     * @param \Krystal\Paginate\PaginatorInterface $paginator
     * @return $this
     */
    final public function setPaginator(PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
        return $this;
    }

    /**
     * Return paginator's instance
     * 
     * @return \Krystal\Paginate\Paginator
     */
    final public function getPaginator()
    {
        return $this->paginator;
    }
    
    /**
     * Creates a configured pagination instance
     * 
     * @param string $url
     * @return \Krystal\Paginate\Paginator
     */
    final public function createPaginator($url)
    {
        $paginator = $this->paginator;
        $paginator->setUrl($url);

        return $paginator;
    }
}

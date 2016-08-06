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

use Krystal\Paginate\PaginatorInterface;
use Krystal\Stdlib\ArrayUtils;
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
     * @return integer
     */
    final public function getLastPk()
    {
        $this->validateShortcutData();

        return $this->db->select()
                        ->max($this->getPk(), 'last')
                        ->from(static::getTableName())
                        ->query('last');
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
        if (!empty($fillable) && !ArrayUtils::keysExist($data, $fillable)) {
            throw new LogicException('Can not persist the entity due to fillable protection. Make sure all fillable keys exist in the entity');
        }

        $this->validateShortcutData();

        if (isset($data[$this->getPk()]) && $data[$this->getPk()]) {
            return $this->db->update(static::getTableName(), $data)
                            ->whereEquals($this->getPk(), $data[$this->getPk()])
                            ->execute();
        } else {
            if (array_key_exists($this->getPk(), $data)) {
                unset($data[$this->getPk()]);
            }

            return $this->db->insert(static::getTableName(), $data)
                            ->execute();
        }
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
        $this->validateShortcutData();

        return $this->db->update(static::getTableName(), array($column => $value))
                        ->whereEquals($this->getPk(), $pk)
                        ->execute();
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

        return $this->db->delete()
                        ->from(static::getTableName())
                        ->whereEquals($column, $value)
                        ->execute();
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
     * @return boolean
     */
    final public function incrementColumnByPk($pk, $column, $step = 1)
    {
        return $this->db->increment(static::getTableName(), $column, $step)
                        ->whereEquals($this->getPk(), $pk)
                        ->execute();
    }

    /**
     * Decrements column's value by associated PK's value
     * 
     * @param string $pk PK's value
     * @param string $column Target column
     * @param integer $step
     * @return boolean
     */
    final public function decrementColumnByPk($pk, $column, $step = 1)
    {
        return $this->db->decrement(static::getTableName(), $column, $step)
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
        if (is_null($table)) {
            $table = static::getTableName();
        }

        return $this->db->dropTable($table, true)
                        ->execute();
    }

    /**
     * Drop several tables at once
     * 
     * @param array $tables
     * @return boolean
     */
    final public function dropTables(array $tables)
    {
        foreach ($tables as $table) {
            if (!$this->dropTable($table)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns last id
     * 
     * @return integer
     */
    public function getLastId()
    {
        return $this->pdo->lastInsertId();
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

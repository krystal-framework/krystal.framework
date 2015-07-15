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

use Krystal\Paginate\PaginatorInterface;

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
	 * State initialization
	 * 
	 * @param \Krystal\Db\Sql\Db
	 * @param \Krystal\Paginate\PaginatorInterface $paginator
	 * @return void
	 */
	public function __construct(Db $db, PaginatorInterface $paginator)
	{
		$this->db = $db;
		$this->setPaginator($paginator);
	}

	/**
	 * @TODO
	 */
	protected function manyToOne()
	{
	}
	
	/**
	 * @TODO
	 */
	protected function oneToOne()
	{
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
	final protected function deleteByPk($pk)
	{
		$this->validateShortcutData();
		return $this->deleteByColumn($this->getPk(), $pk);
	}

	/**
	 * Finds a row by associated PK's value
	 * 
	 * @param string $pk PK's value
	 * @return array
	 */
	final protected function findByPk($pk)
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
	final protected function findColumnByPk($id, $column)
	{
		$this->validateShortcutData();
		return $this->fetchOneColumn($column, $this->getPk(), $id);
	}
	
	/**
	 * Returns last primary key
	 * 
	 * @return integer
	 */
	final protected function getLastPk()
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
	 * @return boolean
	 */
	final protected function persist(array $data)
	{
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
	final protected function updateColumnByPk($pk, $column, $value)
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
	final protected function fetchOneColumn($column, $key, $value)
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
	final protected function fetchColumns($column, $key, $value)
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
	final protected function findAllByColumn($column, $value)
	{
		$this->validateShortcutData();

		return $this->db->select('*')
						->from(static::getTableName())
						->whereEquals($column, $value)
						->queryAll();
	}

	/**
	 * Fetches all row data by column's value
	 * 
	 * @param string $column
	 * @param string $value
	 * @param mixed $select
	 * @return array
	 */
	final protected function fetchAllByColumn($column, $value, $select = '*')
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
	final protected function fetchByColumn($column, $value, $select = '*')
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
	final protected function deleteByColumn($column, $value)
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
	 * @return integer
	 */
	final protected function countByColumn($column, $value, $field = null)
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
	final protected function incrementColumnByPk($pk, $column, $step = 1)
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
	final protected function decrementColumnByPk($pk, $column, $step = 1)
	{
		return $this->db->decrement(static::getTableName(), $column, $step)
						->whereEquals($this->getPk(), $pk)
						->execute();
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
	 * @return object
	 */
	final public function getPaginator()
	{
		return $this->paginator;
	}
}

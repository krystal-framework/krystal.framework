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

interface DbInterface extends QueryBuilderInterface
{
    /**
     * Fetch all tables
     * 
     * @return array
     */
    public function fetchAllTables();

    /**
     * Dump tables into SQL string
     * 
     * @param array $tables If empty current tables will be taken into account
     * @return string
     */
    public function dump(array $tables = array());

    /**
     * Checks whether current driver is a target
     * 
     * @param string $driver
     * @return boolean
     */
    public function isDriver($driver);

    /**
     * Returns name of current PDO driver
     * 
     * @return string
     */
    public function getDriver();

    /**
     * Initiates a transaction
     * 
     * @return boolean
     */
    public function beginTransaction();

    /**
     * Checks if inside a transaction
     * 
     * @return boolean
     */
    public function inTransaction();

    /**
     * Commits a transaction
     * 
     * @return boolean
     */
    public function commit();

    /**
     * Rolls back a transaction
     * 
     * @throws \PDOException if no transaction is active
     * @return boolean
     */
    public function rollBack();

    /**
     * Paginates a result-set without automatic query guessing
     * 
     * @param integer $count
     * @param integer $page Current page
     * @param integer $itemsPerPage Items per page to be shown
     * @return \Krystal\Db\Sql\Db
     */
    public function paginateRaw($count, $page, $itemsPerPage);

    /**
     * Automatically paginates result-set
     * 
     * @param integer $page
     * @param integer $itemsPerPage
     * @param string $column Column to be selected when counting
     * @return \Krystal\Db\Db
     */
    public function paginate($page, $itemsPerPage, $column = '1');

    /**
     * Returns PDO instance
     * 
     * @return \PDO
     */
    public function getPdo();

    /**
     * Returns prepared PDO statement
     * 
     * @return \PDOStatement
     */
    public function getStmt();

    /**
     * Queries for all result-set
     * 
     * @param string $column Optionally can be filtered by a column
     * @param integer $mode Fetch mode. Can be overridden when needed
     * @return array
     */
    public function queryAll($column = null, $mode = null);

    /**
     * Queries for a single result-set
     * 
     * @param string $column
     * @param integer $mode Fetch mode. Can be overridden when needed
     * @return array|string
     */
    public function query($column = null, $mode = null);

    /**
     * Executes a command
     * 
     * @return boolean
     */
    public function execute();
}

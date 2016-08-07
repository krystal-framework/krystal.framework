<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Sql\Relations;

use Krystal\Db\Sql\DbInterface;

final class RelationProcessor implements RelationProcessorInterface
{
    /**
     * Queue of relations
     * 
     * @var array
     */
    private $queue = array();

    /**
     * Database service
     * 
     * @var \Krystal\Db\Sql\DbInterface
     */
    private $db;

    /**
     * A name of the PK in master table
     * 
     * @var string
     */
    private $pk;

    const PARAM_RELATION = 'relation';
    const PARAM_ARGS = 'args';

    /**
     * State initialization
     * 
     * @param \Krystal\Db\Sql\DbInterface $db Database service
     * @return void
     */
    public function __construct(DbInterface $db)
    {
        $this->db = $db;
    }

    /**
     * Append new relation to the queue stack
     * 
     * @param string $relation
     * @param array $args Arguments to be passed on invoking
     * @return void
     */
    public function queue($relation, array $args)
    {
        $this->queue[] = array(
            self::PARAM_RELATION => $relation,
            self::PARAM_ARGS => $args
        );
    }

    /**
     * Checks whether queue is empty or not
     * 
     * @return boolean
     */
    public function hasQueue()
    {
        return !empty($this->queue);
    }

    /**
     * Processes a raw result-set appending relational data if necessary
     * 
     * @param array $rows Target collection of rows
     * @return array
     */
    public function process(array $rows)
    {
        foreach ($this->queue as $queue) {
            // Just references
            $relation = $queue[self::PARAM_RELATION];
            $args = $queue[self::PARAM_ARGS];

            switch ($relation) {
                case 'asOneToMany':
                    $relation = new OneToMany($this->db);

                    // Grab values from arguments passed in $db->asOneToMany()
                    $slaveTable = $args[0];
                    $slaveColumnId = $args[1];
                    $alias = $args[2];

                    $rows = $relation->merge($this->getMasterPkName(), $rows, $alias, $slaveTable, $slaveColumnId);
                break;

                case 'asOneToOne':
                    // Grab values from arguments passed in $db->asOneToOne()
                    $column = $args[0];
                    $alias = $args[1];
                    $table = $args[2];
                    $link = $args[3];

                    $relation = new OneToOne($this->db);
                    $rows = $relation->merge($rows, $column, $alias, $table, $link);
                break;

                case 'asManyToMany':
                    // Grab values from arguments passed in $db->asManyToMany()
                    $alias = $args[0];
                    $junction = $args[1];
                    $column = $args[2];
                    $table = $args[3];
                    $pk = $args[4];
                    $columns = $args[5];

                    $relation = new ManyToMany($this->db);
                    $rows = $relation->merge($this->getMasterPkName(), $alias, $rows, $junction, $column, $table, $pk, $columns);
                break;
            }
        }

        $this->clearQueue();
        return $rows;
    }

    /**
     * Clears the queue
     * 
     * @return void
     */
    private function clearQueue()
    {
        $this->queue = array();
    }

    /**
     * Extracts PK column from a table
     * 
     * @param string $table
     * @return string
     */
    private function extractPkName($table)
    {
        if (is_null($this->pk)) {
            // This has been tested only in MySQL so far
            $row = $this->db->showKeys()
                            ->from($table)
                            ->whereEquals('Key_name', 'PRIMARY')
                            ->getStmt()
                            ->fetch();

            $this->pk = $row['Column_name'];
        }

        return $this->pk;
    }

    /**
     * Returns PK's column name of the master table
     * 
     * @return string
     */
    private function getMasterPkName()
    {
        return $this->extractPkName($this->db->getQueryBuilder()->getSelectedTable());
    }
}

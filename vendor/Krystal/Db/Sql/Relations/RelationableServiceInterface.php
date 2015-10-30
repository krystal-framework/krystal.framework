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

interface RelationableServiceInterface
{
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
    public function asManyToMany($alias, $junction, $column, $table, $pk);

    /**
     * Appends one-to-one grabber to the queue
     * 
     * @param string $column Column name from the master table to be replaced by alias
     * @param string $alias Alias name for the column name being replaced
     * @param string $table Slave table name
     * @param string $link Linking column name from slave table
     * @return \Krystal\Db\Sql\Db
     */
    public function asOneToOne($column, $alias, $table, $link);

    /**
     * Appends one-to-many grabber to the queue
     * 
     * @param string $table Slave table name
     * @param string $pk Column name which is primary key
     * @param string $alias Alias for result-set
     * @return \Krystal\Db\Sql\Db
     */
    public function asOneToMany($table, $pk, $alias);
}

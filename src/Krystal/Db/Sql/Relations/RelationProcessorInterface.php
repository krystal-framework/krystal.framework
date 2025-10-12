<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Sql\Relations;

interface RelationProcessorInterface
{
    /**
     * Append new relation to the queue stack
     * 
     * @param string $relation
     * @param array $args Arguments to be passed on invoking
     * @return void
     */
    public function queue($relation, array $args);

    /**
     * Checks whether queue is empty or not
     * 
     * @return boolean
     */
    public function hasQueue();

    /**
     * Processes a raw result-set appending relational data if necessary
     * 
     * @param array $rows Target collection of rows
     * @return array
     */
    public function process(array $rows);
}

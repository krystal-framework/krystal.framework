<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Sql;

final class QueryLogger implements QueryLoggerInterface
{
    /**
     * All registered queries
     * 
     * @var array
     */
    private $queries = array();

    /**
     * Adds a query to the stack
     * 
     * @param string $query
     * @return void
     */
    public function add($query)
    {
        array_push($this->queries, $query);
    }

    /**
     * Returns all queries
     * 
     * @return array
     */
    public function getAll()
    {
        return $this->queries;
    }

    /**
     * Counts amount of queries in the stack
     * 
     * @return integer
     */
    public function getCount()
    {
        return count($this->queries);
    }
}

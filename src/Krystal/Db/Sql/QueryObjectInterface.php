<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Sql;

interface QueryObjectInterface
{
    /**
     * Returns a name of selected table
     * 
     * @throws \LogicException if no table is selected
     * @return string
     */
    public function getSelectedTable();

    /**
     * Returns a word with wildcard. Can be used for LIKE constraints
     * 
     * @param string $target
     * @param string $type
     * @throws \RuntimeException if unknown type supplied
     * @return string
     */
    public function getWithWildcart($target, $type = self::LIKE_RAW);

    /**
     * Checks whether it's worth filtering
     * 
     * @param boolean $state
     * @param string|array $target
     * @return boolean
     */
    public function isFilterable($state, $target);

    /**
     * Guesses count query
     * 
     * @param string $column Column to be selected
     * @param string $alias
     * @return string Guessed query
     */
    public function guessCountQuery($column, $alias);

    /**
     * Sets query raw string
     * 
     * @param string $queryString
     * @return void
     */
    public function setQueryString($queryString);

    /**
     * Returns current query string
     * 
     * @return string
     */
    public function getQueryString();

    /**
     * Clears the query string
     * 
     * @return \Krystal\Db\Sql\QueryBuilder
     */
    public function clear();
}

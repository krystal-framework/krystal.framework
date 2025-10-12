<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Filter;

interface QueryContainerInterface
{
    /**
     * Determines whether a column has been sorted
     * 
     * @param string $column Column name
     * @return boolean
     */
    public function isSortedBy($column);

    /**
     * Determines whether a column has been sorted by ASC method
     * 
     * @param string $column Column name
     * @return boolean
     */
    public function isSortedByAsc($column);

    /**
     * Determines whether a column has been sorted by DESC method
     * 
     * @param string $column Column name
     * @return boolean
     */
    public function isSortedByDesc($column);

    /**
     * Returns sorting URL for a particular column
     * 
     * @param string $column Column name
     * @return string
     */
    public function getColumnSortingUrl($column);

    /**
     * Returns grouped element name
     * 
     * @param string $name
     * @return string
     */
    public function getElementName($name);

    /**
     * Checks whether a filter has been applied
     * 
     * @return boolean
     */
    public function isApplied();

    /**
     * Returns key's value if exists
     * 
     * @param string $key
     * @return string
     */
    public function get($key);
}

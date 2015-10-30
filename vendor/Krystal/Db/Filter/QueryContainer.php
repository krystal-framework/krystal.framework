<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Filter;

final class QueryContainer implements QueryContainerInterface
{
    /**
     * Query filter data
     * 
     * @var array
     */
    private $data = array();

    /**
     * Raw query data
     * 
     * @var array
     */
    private $query;

    /**
     * Base route
     * 
     * @var string
     */
    private $route;

    /**
     * State initialization
     * 
     * @param array $query Raw query data
     * @param string $route Base route
     * @return void
     */
    public function __construct(array $query, $route)
    {
        if (isset($query[FilterInvoker::FILTER_PARAM_NS]) && is_array($query[FilterInvoker::FILTER_PARAM_NS])) {
            $this->data = $query[FilterInvoker::FILTER_PARAM_NS];
        }

        $this->route = $route;
        $this->query = $query;
    }

    /**
     * Returns parameter's value from query
     * 
     * @param string $param
     * @param mixed $default
     * @return string
     */
    private function getParam($param, $default = null)
    {
        if (isset($this->query[$param])) {
            return $this->query[$param];
        } else {
            return $default;
        }
    }

    /**
     * Returns current page number
     * 
     * @return integer
     */
    private function getCurrentPageNumber()
    {
        return (int) $this->getParam(FilterInvoker::FILTER_PARAM_PAGE, 1);
    }

    /**
     * Determines whether a column has been sorted either by ASC or DESC method
     * 
     * @param string $column Column name
     * @param string $value
     * @return boolean
     */
    private function isSortedByMethod($column, $value)
    {
        return $this->isSortedBy($column) && $this->getParam(FilterInvoker::FILTER_PARAM_DESC) == $value;
    }

    /**
     * Determines whether a column has been sorted
     * 
     * @param string $column Column name
     * @return boolean
     */
    public function isSortedBy($column)
    {
        return $this->getParam(FilterInvoker::FILTER_PARAM_SORT) == $column;
    }

    /**
     * Determines whether a column has been sorted by ASC method
     * 
     * @param string $column Column name
     * @return boolean
     */
    public function isSortedByAsc($column)
    {
        return $this->isSortedByMethod($column, '0');
    }

    /**
     * Determines whether a column has been sorted by DESC method
     * 
     * @param string $column Column name
     * @return boolean
     */
    public function isSortedByDesc($column)
    {
        return $this->isSortedByMethod($column, '1');
    }

    /**
     * Returns sorting URL for a particular column
     * 
     * @param string $column Column name
     * @return string
     */
    public function getColumnSortingUrl($column)
    {
        $data = array(
            FilterInvoker::FILTER_PARAM_PAGE => $this->getCurrentPageNumber(), 
            FilterInvoker::FILTER_PARAM_DESC => !$this->isSortedByDesc($column), 
            FilterInvoker::FILTER_PARAM_SORT => $column
        );

        $generator = new QueryGenerator($this->route, '(:var)');
        return $generator->generate(array_merge($this->query, $data));
    }

    /**
     * Returns grouped element name
     * 
     * @param string $name
     * @return string
     */
    public function getElementName($name)
    {
        return sprintf('%s[%s]', FilterInvoker::FILTER_PARAM_NS, $name);
    }

    /**
     * Checks whether a filter has been applied
     * 
     * @return boolean
     */
    public function isApplied()
    {
        return !empty($this->data);
    }

    /**
     * Returns key's value if exists
     * 
     * @param string $key
     * @return string
     */
    public function get($key)
    {
        $default = false;

        if (!is_array($this->data)) {
            return $default;
        }

        if (isset($this->data[$key])) {
            return $this->data[$key];
        } else {
            return $default;
        }
    }
}

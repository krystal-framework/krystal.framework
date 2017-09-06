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

use Krystal\Paginate\PaginatorInterface;

final class FilterInvoker implements FilterInvokerInterface
{
    const FILTER_PARAM_PAGE = 'page';
    const FILTER_PARAM_DESC = 'desc';
    const FILTER_PARAM_SORT = 'sort';
    const FILTER_PARAM_NS = 'filter';

    /**
     * Query data
     * 
     * @var array
     */
    private $input = array();

    /**
     * Target route
     * 
     * @var string
     */
    private $route;

    /**
     * State initialization
     * 
     * @param array $input Query data
     * @param string $route
     * @return void
     */
    public function __construct(array $input, $route)
    {
        $this->input = $input;
        $this->route = $route;
    }

    /**
     * Generates URL
     * 
     * @param array $data
     * @param string $route
     * @return string
     */
    public static function createUrl(array $data, $route)
    {
        // Query start
        $placeholder = '(:var)';
        $url = '?';

        $url .= http_build_query($data);
        $url = str_replace(rawurlencode($placeholder), $placeholder, $url);

        return $route.$url;
    }

    /**
     * Invokes a filter
     * 
     * @param \Krystal\Db\Filter\FilterableServiceInterface $service
     * @param integer $perPageCount Amount of items to be display per page
     * @param array $parameters Custom user-defined parameters
     * @return array
     */
    public function invoke(FilterableServiceInterface $service, $perPageCount, array $parameters = array())
    {
        $page = $this->getPageNumber();
        $sort = $this->getSortingColumn();
        $desc = $this->getDesc();

        $records = $service->filter($this->getData(), $page, $perPageCount, $sort, $desc, $parameters);

        // Tweak pagination if available
        if (method_exists($service, 'getPaginator')) {
            $paginator = $service->getPaginator();

            if ($paginator instanceof PaginatorInterface) {
                $paginator->setUrl($this->getPaginationUrl($page, $sort, $desc));
            }
        }

        return $records;
    }

    /**
     * Checks whether query has at least non-empty value
     * 
     * @return boolean
     */
    private function hasQueryVals()
    {
        foreach ($this->input as $key => $value) {
            // If it's positive-like, then stop returning true
            if ($value == '0' || $value) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns pagination URL
     * 
     * @param integer $page Current page number
     * @param string $sort Current sorting column name
     * @param string $desc
     * @return string
     */
    private function getPaginationUrl($page, $sort, $desc)
    {
        $placeholder = '(:var)';

        $data = array(
            self::FILTER_PARAM_PAGE => $placeholder, 
            self::FILTER_PARAM_DESC => $desc, 
            self::FILTER_PARAM_SORT => $sort
        );

        return self::createUrl(array_merge($this->input, $data), $this->route);
    }

    /**
     * Returns whether should be sorted in DESC order
     * 
     * @return string
     */
    private function getDesc()
    {
        return $this->getCurrentParam(self::FILTER_PARAM_DESC, '1');
    }

    /**
     * Returns current sorting column name
     * 
     * @return string
     */
    private function getSortingColumn()
    {
        return $this->getCurrentParam(self::FILTER_PARAM_SORT, false);
    }

    /**
     * Returns current page number
     * 
     * @return integer
     */
    private function getPageNumber()
    {
        return (int) $this->getCurrentParam(self::FILTER_PARAM_PAGE, 1);
    }

    /**
     * Returns current input parameter value
     * 
     * @param string $param
     * @param mixed $default Default value to be returned if requested one doesn't exist
     * @return string
     */
    private function getCurrentParam($param, $default)
    {
        if (isset($this->input[$param])) {
            return $this->input[$param];
        } else {
            return $default;
        }
    }

    /**
     * Returns data to be supplied to user-defined filtering method
     * 
     * @return \Krystal\Db\Filter\InputDecorator
     */
    private function getData()
    {
        if (isset($this->input[self::FILTER_PARAM_NS])) {
            $data = $this->input[self::FILTER_PARAM_NS];
        } else {
            $data = array();
        }

        return new InputDecorator($data);
    }
}

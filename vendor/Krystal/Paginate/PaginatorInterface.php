<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Paginate;

interface PaginatorInterface
{
    /**
     * Tweaks the paginator before it can be used
     * 
     * @param integer $totalAmount Total amount of records
     * @param integer $itemsPerPage Per page count
     * @param integer $page Current page
     * @return void
     */
    public function tweak($totalAmount, $itemsPerPage, $page);

    /**
     * Defines a permanent URL
     * 
     * @param string $url
     * @return void
     */
    public function setUrl($url);

    /**
     * Returns permanent URL substituting a placeholder with a page number
     * 
     * @param integer $page
     * @return string
     */
    public function getUrl($page);

    /**
     * Returns the first page i.e always 1
     * 
     * @return integer
     */
    public function getFirstPage();

    /**
     * Returns the last page
     * 
     * @return integer
     */
    public function getLastPage();

    /**
     * Counts the offset
     * 
     * @return integer
     */
    public function countOffset();

    /**
     * Returns a summary
     * 
     * @param string $separator
     * @return string
     */
    public function getSummary($separator = '-');

    /**
     * Returns the start
     * 
     * @return integer
     */
    public function getStart();

    /**
     * Returns the end
     * 
     * @return integer
     */
    public function getEnd();

    /**
     * Resets the paginator's instance
     * 
     * @return void
     */
    public function reset();

    /**
     * Checks whether its a current page
     * 
     * @param integer $page
     * @return boolean
     */
    public function isCurrentPage($page);

    /**
     * Checks whether there's at least one page before doing pagination
     * 
     * @return boolean
     */
    public function hasPages();

    /**
     * Checks whether there's a  style adapter
     * 
     * @return boolean
     */
    public function hasAdapter();

    /**
     * Returns total page numbers
     * Style-adapter aware method
     * 
     * @return array
     */
    public function getPageNumbers();

    /**
     * Checks if next page number is available
     * 
     * @return boolean
     */
    public function hasNextPage();

    /**
     * Checks if previous page number is available
     * 
     * @return boolean
     */
    public function hasPreviousPage();

    /**
     * Returns next page number
     * 
     * @return integer
     */
    public function getNextPage();

    /**
     * Returns previous page number
     * 
     * @return integer
     */
    public function getPreviousPage();

    /**
     * Returns the URL of the next page
     * 
     * @return string
     */
    public function getNextPageUrl();

    /**
     * Return the URL of the previous page
     * 
     * @return string
     */
    public function getPreviousPageUrl();

    /**
     * Returns current page number
     * 
     * @throws \RuntimeException If current page hasn't been defined
     * @return integer
     */
    public function getCurrentPage();

    /**
     * Returns defined per page count
     * 
     * @throws \RuntimeException if items per page were not defined
     * @return integer
     */
    public function getItemsPerPage();

    /**
     * Returns total amount of pages
     * 
     * @throws \RuntimeException if amount of items was not defined
     * @return integer
     */
    public function getTotalAmount();
}

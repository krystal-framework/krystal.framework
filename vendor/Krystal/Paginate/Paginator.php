<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Paginate;

use RuntimeException;
use Krystal\Paginate\Style\StyleInterface;
use Krystal\Paginate\PaginatorInterface;

/**
 * Order of the execution:
 * 
 * 1. Define total amount of records
 *    $paginator->setTotalAmount();
 * 
 * 2. Set items per page
 *    $paginator->setItemsPerPage();
 * 
 * 3. Use $paginator->countOffset() when building SQL query
 * 
 */
class Paginator implements PaginatorInterface
{
	/**
	 * Optional pagination style adapter
	 * 
	 * @var \Krystal\Paginate\Style\StyleInterface
	 */
	protected $style;

	/**
	 * Total amount of records
	 * 
	 * @var integer
	 */
	protected $totalAmount;

	/**
	 * Per page count
	 * 
	 * @var integer
	 */
	protected $itemsPerPage;

	/**
	 * Permanent page's URL
	 * 
	 * @var string
	 */
	protected $url;

	/**
	 * State initialization
	 * 
	 * @param \Krystal\Paginate\Style\StyleInterface $style
	 * @return void
	 */
	public function __construct(StyleInterface $style = null)
	{
		$this->style = $style;
	}

	/**
	 * Defines a permanent url
	 * 
	 * @param string $url
	 * @return void
	 */
	public function setUrl($url)
	{
		$this->url = $url;
	}

	/**
	 * Returns permanent URL substituting a placeholder with a page number
	 * 
	 * @param integer $page
	 * @return string
	 */
	public function getUrl($page)
	{
		//@TODO Replace placeholder sign
		if (strpos($this->url, '%s') === false) {
			throw new \Exception('Page URL must contain one placeholder');
		}
		
		$page = (string) $page;
		
		return str_replace('%s', $page, $this->url);
	}

	/**
	 * Returns first page i.e always 1
	 * 
	 * @return integer
	 */
	public function getFirstPage()
	{
		return 1;
	}

	/**
	 * Returns the last page
	 * 
	 * @return integer
	 */
	public function getLastPage()
	{
		$pages = $this->getPageNumbers();
		return array_pop($pages);
	}

	/**
	 * Returns an offset
	 * 
	 * @return integer
	 */
	public function countOffset()
	{
		return ($this->getCurrentPage() - 1) * $this->getItemsPerPage();
	}

	/**
	 * Returns a summary
	 * 
	 * @param string $separator
	 * @return string
	 */
	public function getSummary($separator = '-')
	{
		if ($this->getTotalAmount() == 0) {
			return (string) 0;
		} else {
			return $this->getStart() . $separator . $this->getEnd();
		}
	}

	/**
	 * Returns the start
	 * 
	 * @return integer
	 */
	public function getStart()
	{
		return $this->countOffset() + 1;
	}

	/**
	 * Returns the end
	 * 
	 * @return integer
	 */
	public function getEnd()
	{
		return min($this->getTotalAmount(), $this->getStart() + ($this->getItemsPerPage() - 1));
	}

	/**
	 * Resets paginator instance
	 * 
	 * @return void
	 */
	public function reset()
	{
		$this->itemsPerPage = null;
		$this->currentPage = null;
		$this->totalAmount = null;
		$this->url = null;
	}

	/**
	 * Checks whether its a current page
	 * 
	 * @param integer $page
	 * @return boolean
	 */
	public function isCurrentPage($page)
	{
		return $this->getCurrentPage() == $page;
	}

	/**
	 * Checks whether we have at least one page to paginate
	 * 
	 * @return boolean
	 */
	public function hasPages()
	{
		return $this->getPagesCount() > 1;
	}

	/**
	 * Checks whether we have a style adapter
	 * 
	 * @return boolean
	 */
	public function hasAdapter()
	{
		return $this->style instanceOf StyleInterface;
	}

	/**
	 * Return total page numbers
	 * Style-adapter aware method
	 * 
	 * @return array
	 */
	public function getPageNumbers()
	{
		$pages = range(1, $this->getPagesCount());

		// And has an styling adapter
		if ($this->getPagesCount() > 10 && $this->hasAdapter()) {
			return $this->style->getPageNumbers($pages, $this->getCurrentPage());
		} else {
			return $pages;
		}
	}

	/**
	 * Checks whether we have a next page
	 * 
	 * @return boolean
	 */
	public function hasNextPage()
	{
		return $this->getNextPage() - $this->getCurrentPage() == 1;
	}

	/**
	 * Checks if we have a previous page
	 * 
	 * @return boolean
	 */
	public function hasPreviousPage()
	{
		return $this->getCurrentPage() - $this->getPreviousPage() == 1;
	}

	/**
	 * Returns next page number
	 * 
	 * @return integer
	 */
	public function getNextPage()
	{
		if ($this->getPagesCount() > $this->getCurrentPage()) {
			return $this->getCurrentPage() + 1;
		} else {
			return $this->getPagesCount();
		}
	}

	/**
	 * Returns previous page number
	 * 
	 * @return integer
	 */
	public function getPreviousPage()
	{
		if ($this->getCurrentPage() > 1) {
			return $this->getCurrentPage() - 1;
		} else {
			return 1;
		}
	}

	/**
	 * Defines current page
	 * 
	 * @param integer|string $currentPage
	 * @param boolean $fixRange Whether page range should be fixed if wrong one supplied
	 * @return \Krystal\Paginate\Paginator
	 */
	public function setCurrentPage($currentPage, $fixRange = true)
	{
		if ($fixRange === true) {
			if (!$this->pageInRange($currentPage)) {
				$currentPage = 1;
			}
		}

		$this->currentPage = (int) $currentPage;
		return $this;
	}

	/**
	 * Returns current page
	 * 
	 * @throws \RuntimeException If current page hasn't been defined
	 * @return integer
	 */
	public function getCurrentPage()
	{
		if (is_null($this->currentPage)) {
			throw new RuntimeException('Define current page first');
		}

		return $this->currentPage;
	}

	/**
	 * Count total amount of pages
	 * 
	 * @return integer
	 */
	public function getPagesCount()
	{
		if ($this->getItemsPerPage() != 0) {
			return (int) abs(ceil($this->getTotalAmount() / $this->getItemsPerPage()));
		} else {
			return 0;
		}
	}

	/**
	 * Checks whether page is in range
	 * 
	 * @param integer|string $page
	 * @return boolean
	 */
	private function pageInRange($page)
	{
		$page = (int) $page;
		return $this->getPagesCount() >= $page;
	}

	/**
	 * Set items per page
	 * 
	 * @param string|integer $itemsPerPage
	 * @return \Krystal\Paginate\Paginator
	 */
	public function setItemsPerPage($itemsPerPage)
	{
		$this->itemsPerPage = $itemsPerPage;
		return $this;
	}

	/**
	 * Return items per page
	 * 
	 * @throws \RuntimeException if items per page were not defined
	 * @return integer
	 */
	public function getItemsPerPage()
	{
		if (is_null($this->itemsPerPage)) {
			throw new RuntimeException('Define items to show per page first');
		}
		
		return $this->itemsPerPage;
	}

	/**
	 * Defines total amount of records
	 * 
	 * @param string|integer $totalAmount
	 * @return \Krystal\Paginate\Paginator
	 */
	public function setTotalAmount($totalAmount)
	{
		$this->totalAmount = (int) $totalAmount;
		return $this;
	}

	/**
	 * Return total amount of pages
	 * 
	 * @throws \RuntimeException if amount of items was not defined
	 * @return integer
	 */
	public function getTotalAmount()
	{
		if (is_null($this->totalAmount)) {
			throw new RuntimeException('Define total amount of records first');
		}

		return $this->totalAmount;
	}
}

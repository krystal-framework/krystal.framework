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
	 * Tweaks the paginator before it can be used
	 * 
	 * @param integer $totalAmount Total amount of records
	 * @param integer $itemsPerPage Per page count
	 * @param integer $page Current page
	 * @return void
	 */
	public function tweak($totalAmount, $itemsPerPage, $page)
	{
		// Order is this
		$this->totalAmount = (int) $totalAmount;
		$this->itemsPerPage = (int) $itemsPerPage;
		$this->setCurrentPage($page);
	}

	/**
	 * Defines a permanent URL
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
		if (strpos($this->url, '%s') === false) {
			throw new \Exception('Page URL must contain one placeholder');
		}

		//@TODO Improve this ugly hack
		$page = (string) $page;
		return str_replace('%s', $page, $this->url);
	}

	/**
	 * Returns the first page i.e always 1
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
		return (int) array_pop($pages);
	}

	/**
	 * Counts the offset
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
	 * Resets the paginator's instance
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
	 * Checks whether there's at least one page before doing pagination
	 * 
	 * @return boolean
	 */
	public function hasPages()
	{
		return $this->getPagesCount() > 1;
	}

	/**
	 * Checks whether there's a  style adapter
	 * 
	 * @return boolean
	 */
	public function hasAdapter()
	{
		return $this->style instanceOf StyleInterface;
	}

	/**
	 * Returns total page numbers
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
	 * Checks if next page number is available
	 * 
	 * @return boolean
	 */
	public function hasNextPage()
	{
		return $this->getNextPage() - $this->getCurrentPage() == 1;
	}

	/**
	 * Checks if previous page number is available
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
	private function setCurrentPage($currentPage, $fixRange = true)
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
	 * Count total amount of pages
	 * 
	 * @return integer
	 */
	private function getPagesCount()
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
	 * Returns the URL of the next page
	 * 
	 * @return string
	 */
	public function getNextPageUrl()
	{
		return $this->getUrl($this->getNextPage());
	}

	/**
	 * Return the URL of the previous page
	 * 
	 * @return string
	 */
	public function getPreviousPageUrl()
	{
		return $this->getUrl($this->getPreviousPage());
	}

	/**
	 * Returns current page number
	 * 
	 * @return integer
	 */
	public function getCurrentPage()
	{
		return $this->currentPage;
	}

	/**
	 * Returns defined per page count
	 * 
	 * @return integer
	 */
	public function getItemsPerPage()
	{
		return $this->itemsPerPage;
	}

	/**
	 * Returns total amount of pages
	 * 
	 * @return integer
	 */
	public function getTotalAmount()
	{
		return $this->totalAmount;
	}
}

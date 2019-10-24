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

use RuntimeException;
use LogicException;
use Krystal\Paginate\Style\StyleInterface;

final class Paginator implements PaginatorInterface
{
    /**
     * Optional pagination style adapter
     * 
     * @var \Krystal\Paginate\Style\StyleInterface
     */
    private $style;

    /**
     * Total amount of records
     * 
     * @var integer
     */
    private $totalAmount;

    /**
     * Per page count
     * 
     * @var integer
     */
    private $itemsPerPage;

    /**
     * Permanent page's URL
     * 
     * @var string
     */
    private $url;

    /**
     * Current URI string
     * 
     * @var string
     */
    private $uri;

    /**
     * Placeholder to be replaced with actual page number in URL
     * 
     * @var string
     */
    private $placeholder;

    /**
     * State initialization
     * 
     * @param \Krystal\Paginate\Style\StyleInterface $style Optional style adapter
     * @param string $uri Current URI string
     * @param string $placeholder
     * @return void
     */
    public function __construct(StyleInterface $style = null, $uri, $placeholder = '(:var)')
    {
        $this->style = $style;
        $this->uri = $uri;
        $this->placeholder = $placeholder;
    }

    /**
     * Adjusts URL from URI string to make entire pagination work
     * 
     * @return boolean Depending on success
     */
    private function tweakUrl()
    {
        $parsed = parse_url($this->uri);

        // If could parse current URI, process the rest
        if (isset($parsed['path'])) {
            // 1. Parse query string
            if (isset($parsed['query'])) {
                parse_str($parsed['query'], $result);
                $query = $result;
            } else {
                $query = array();
            }

            // 2. Append page parameter to current query string
            $params = array_replace_recursive($query, array('page' => $this->placeholder));

            // 3. Build current URI appending page parameter
            $url = $parsed['path'] . '?' . http_build_query($params);
            $url = str_replace(rawurlencode($this->placeholder), $this->placeholder, $url);

            // 4. Assign prepared URL
            $this->setUrl($url);

            return true;
        } else {
            // Failed to parse URI
            return false;
        }
    }

    /**
     * Tweaks the service before it can be used
     * 
     * @param integer $totalAmount Total amount of records
     * @param integer $itemsPerPage Per page count
     * @param integer $page Current page
     * @return \Krystal\Paginate\Paginator
     */
    public function tweak($totalAmount, $itemsPerPage, $page)
    {
        $this->totalAmount = (int) $totalAmount;
        $this->itemsPerPage = (int) $itemsPerPage;
        $this->setCurrentPage($page);

        // Dynamic tweak by default
        $this->tweakUrl();

        return $this;
    }

    /**
     * Defines a permanent URL
     * 
     * @param string $url
     * @return \Krystal\Paginate\Paginator
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Returns permanent URL substituting a placeholder with current page number
     * 
     * @param integer $page Current page number
     * @throws \RuntimeException If URL isn't defined
     * @throws \LogicException In case the URL string hasn't a placeholder
     * @return string
     */
    public function getUrl($page)
    {
        if (is_null($this->url)) {
            throw new RuntimeException('URL template must be defined');
        }

        if (strpos($this->url, $this->placeholder) !== false) {
            return str_replace($this->placeholder, $page, $this->url);
        } else {
            // Without placeholder, no substitution is done, therefore pagination links won't work
            throw new LogicException('The URL string must contain at least one placeholder to make pagination links work');
        }
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
     * @return \Krystal\Paginate\Paginator
     */
    public function reset()
    {
        $this->itemsPerPage = null;
        $this->currentPage = null;
        $this->totalAmount = null;
        $this->url = null;

        return $this;
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

    /**
     * Returns current state as array representation
     * 
     * @return array
     */ 
    public function toArray()
    {
        return array(
            'firstPage' => $this->getFirstPage(),
            'lastPage' => $this->getLastPage(),
            'offset' => $this->countOffset(),
            'summary' => $this->getSummary(),
            'hasPages' => $this->hasPages(),
            'hasAdapter' => $this->hasAdapter(),
            'hasNextPage' => $this->hasNextPage(),
            'hasPreviousPage' => $this->hasPreviousPage(),
            'nextPage' => $this->getNextPage(),
            'previousPage' => $this->getPreviousPage(),
            'nextPageUrl' => $this->getNextPageUrl(),
            'previousPageUrl' => $this->getPreviousPageUrl(),
            'currentPage' => $this->getCurrentPage(),
            'perPageCount' => $this->getItemsPerPage(),
            'total' => $this->getTotalAmount(),
            'pageNumbers' => $this->getPageNumbers()
        );
    }

    /**
     * Returns current state as JSON representation
     * 
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }
}

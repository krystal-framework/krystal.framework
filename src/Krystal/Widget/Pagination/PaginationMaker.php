<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Widget\Pagination;

use Krystal\Paginate\PaginatorInterface;
use Krystal\Widget\AbstractListWidget;

final class PaginationMaker extends AbstractListWidget
{
    /**
     * Any compliant pagination instance
     * 
     * @var \Krystal\Paginate\PaginatorInterface
     */
    private $paginator;

    /**
     * State initialization
     * 
     * @param \Krystal\Paginate\PaginatorInterface $paginator
     * @param array $options
     * @return void
     */
    public function __construct(PaginatorInterface $paginator, array $options = array())
    {
        $this->paginator = $paginator;
        $this->options = $options;
    }

    /**
     * Renders pagination block
     * 
     * @return string
     */
    public function render()
    {
        // Run only in case paginator has at least one page
        if ($this->paginator->hasPages()) {
            $ulClass = $this->getOption('ulClass', 'pagination');
            return $this->renderList($ulClass, $this->createPaginationItems());
        }
    }

    /**
     * Create navigation items
     * 
     * @return array
     */
    private function createPaginationItems()
    {
        // Grab overrides if present
        $linkClass = $this->getOption('linkClass', 'page-link');
        $itemClass = $this->getOption('itemClass', 'page-item');
        $itemActiveClass = $this->getOption('itemActiveClass', 'page-item active');
        $previousCaption = ' &laquo; ' . $this->getOption('previousCaption', '');
        $nextCaption = $this->getOption('nextCaption', '') . ' &raquo; ';

        // To be returned
        $items = array();

        // First - check if first previous page available
        if ($this->paginator->hasPreviousPage()) {
            $items[] = $this->createListItem($itemClass, $this->renderLink($previousCaption, $this->paginator->getPreviousPageUrl(), $linkClass));
        }

        foreach ($this->paginator->getPageNumbers() as $page) {
            if (is_numeric($page)) {
                $currentClass = $this->paginator->isCurrentPage($page) ? $itemActiveClass : $itemClass;
                $items[] = $this->createListItem($currentClass, $this->renderLink($page, $this->paginator->getUrl($page), $linkClass));
            } else {
                $items[] = $this->createListItem($itemClass, $this->renderLink($page, '#', $linkClass));
            }
        }

        // Last - check if next page available
        if ($this->paginator->hasNextPage()) {
            $items[] = $this->createListItem($itemClass, $this->renderLink($nextCaption, $this->paginator->getNextPageUrl(), $linkClass));
        }

        return $items;
    }
}

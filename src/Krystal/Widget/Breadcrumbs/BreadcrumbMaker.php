<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Widget\Breadcrumbs;

use Krystal\Widget\AbstractListWidget;
use Krystal\Form\Navigation\Breadcrumbs\BreadcrumbBagInterface;
use Krystal\I18n\TranslatorInterface;

final class BreadcrumbMaker extends AbstractListWidget
{
    /**
     * Any compliant breadcrumbbag instance
     * 
     * @var \Krystal\Form\Navigation\Breadcrumbs\BreadcrumbBagInterface
     */
    private $breadcrumbBag;

    /**
     * Any compliant translator instance
     * 
     * @var \Krystal\I18n\TranslatorInterface
     */
    private $translator;

    /**
     * State initialization
     * 
     * @param \Krystal\Form\Navigation\Breadcrumbs\BreadcrumbBagInterface $breadcrumbBag
     * @param \Krystal\I18n\TranslatorInterface $translator
     * @param array $options
     * @return void
     */
    public function __construct(BreadcrumbBagInterface $breadcrumbBag, TranslatorInterface $translator, array $options = array())
    {
        $this->breadcrumbBag = $breadcrumbBag;
        $this->translator = $translator;
        $this->options = $options;
    }

    /**
     * Render breadcrumbs
     * 
     * @return string
     */
    public function render()
    {
        // Run only in case there are breadcrumbs
        if ($this->breadcrumbBag->has()) {
            $ulClass = $this->getOption('ulClass', 'breadcrumb');
            return $this->renderList($ulClass, $this->createBreadcrumbItems());
        }
    }

    /**
     * Create breadcrumb nodes
     * 
     * @return array
     */
    private function createBreadcrumbItems()
    {
        $itemClass = $this->getOption('itemClass', null);
        $itemActiveClass = $this->getOption('itemActiveClass', 'active');
        $linkClass = $this->getOption('linkClass', null);

        $items = array();

        foreach ($this->breadcrumbBag->getBreadcrumbs() as $breadcrumb) {
            if ($breadcrumb->isActive()) {
                $items[] = $this->createListItem($itemActiveClass, $breadcrumb->getName());
            } else {
                $items[] = $this->createListItem($itemClass, $this->renderLink($breadcrumb->getName(), $breadcrumb->getLink(), $linkClass));
            }
        }

        return $items;
    }
}

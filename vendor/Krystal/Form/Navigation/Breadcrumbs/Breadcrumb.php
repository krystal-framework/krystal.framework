<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Navigation\Breadcrumbs;

final class Breadcrumb implements BreadcrumbInterface
{
    /**
     * Breadcrumb's name itself
     * 
     * @var string
     */
    private $name;

    /**
     * Intended link to a breadcrumb
     * 
     * @var string
     */
    private $link;

    /**
     * Whether a breadcrumb is active or not
     * 
     * @var boolean
     */
    private $active;

    /**
     * Whether breadcrumb is first
     * 
     * @var boolean
     */
    private $first;

    /**
     * Defines whether a breadcrumb must be first
     * 
     * @param boolean $first
     * @return \Krystal\Form\Navigation\Breadcrumbs\Breadcrumb
     */
    public function setFirst($first)
    {
        $this->first = (bool) $first;
        return $this;
    }

    /**
     * Tells whether a breadcrumb is a first in collection
     * 
     * @return boolean
     */
    public function isFirst()
    {
        return $this->first;
    }

    /**
     * Defines breadcrumb's name
     * 
     * @param string $name
     * @return \Krystal\Form\Navigation\Breadcrumbs\Breadcrumb
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Returns breadcrumb's name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Defines breadcrumb's link
     * 
     * @param string $link
     * @return \Krystal\Form\Navigation\Breadcrumbs\Breadcrumb
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * Returns breadcrumb's link
     * 
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Defines whether a breadcrumb must be active or not
     * 
     * @param boolean $active
     * @return \Krystal\Form\Navigation\Breadcrumbs\Breadcrumb
     */
    public function setActive($active)
    {
        $this->active = (bool) $active;
        return $this;
    }

    /**
     * Tells whether a breadcrumb is active
     * 
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Navigation\Breadcrumbs;

interface BreadcrumbBagInterface
{
    /**
     * Removes first breadcrumb
     * 
     * @return \Krystal\Form\Navigation\BreadcrumbBag
     */
    public function removeFirst();

    /**
     * Adds breadcrumb collection
     * 
     * @param array $collection
     * @return void
     */
    public function add(array $collection);

    /**
     * Appends one breadcrumb
     * 
     * @param string $name Breadcrumb name
     * @param string $link Breadcrumb link
     * @return \Krystal\Form\Navigation\BreadcrumbBag
     */
    public function addOne($name, $link = '#');

    /**
     * Checks whether breadcrumb bag is empty
     * 
     * @return boolean
     */
    public function has();

    /**
     * Clears the collection
     * 
     * @return void
     */
    public function clear();

    /**
     * Returns an array of all registred breadcrumb names
     * 
     * @return array
     */
    public function getNames();

    /**
     * Returns first breadcrumb name
     * 
     * @return string
     */
    public function getFirstName();

    /**
     * Returns last breadcrumb name
     * 
     * @return string
     */
    public function getLastName();

    /**
     * Returns breadcrumb collection
     * 
     * @return array
     */
    public function getBreadcrumbs();
}

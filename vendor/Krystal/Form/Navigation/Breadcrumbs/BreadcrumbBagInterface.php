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
	 * Returns breadcrumb collection
	 * 
	 * @return array
	 */
	public function getBreadcrumbs();
}

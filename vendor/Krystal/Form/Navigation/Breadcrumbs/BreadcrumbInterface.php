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

interface BreadcrumbInterface
{
	/**
	 * Defines whether a breadcrumb must be first
	 * 
	 * @param boolean $first
	 * @return \Krystal\Form\Navigation\Breadcrumbs\Breadcrumb
	 */
	public function setFirst($first);

	/**
	 * Tells whether a breadcrumb is a first in collection
	 * 
	 * @return boolean
	 */
	public function isFirst();

	/**
	 * Defines breadcrumb's name
	 * 
	 * @param string $name
	 * @return \Krystal\Form\Navigation\Breadcrumbs\Breadcrumb
	 */
	public function setName($name);

	/**
	 * Returns breadcrumb's name
	 * 
	 * @return string
	 */
	public function getName();

	/**
	 * Defines breadcrumb's link
	 * 
	 * @param string $link
	 * @return \Krystal\Form\Navigation\Breadcrumbs\Breadcrumb
	 */
	public function setLink($link);

	/**
	 * Returns breadcrumb's link
	 * 
	 * @return string
	 */
	public function getLink();

	/**
	 * Defines whether a breadcrumb must be active or not
	 * 
	 * @param boolean $active
	 * @return \Krystal\Form\Navigation\Breadcrumbs\Breadcrumb
	 */
	public function setActive($active);

	/**
	 * Tells whether a breadcrumb is active
	 * 
	 * @return boolean
	 */
	public function isActive();
}

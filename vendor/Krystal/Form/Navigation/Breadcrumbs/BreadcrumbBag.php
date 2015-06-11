<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Navigation\Breadcrumbs;

use Krystal\Stdlib\ArrayUtils;

final class BreadcrumbBag implements BreadcrumbBagInterface
{
	/**
	 * A collection is breadcrumb instances
	 * 
	 * @var array
	 */
	private $collection = array();

	/**
	 * State initialization
	 * 
	 * @param array $collection
	 * @return void
	 */
	public function __construct(array $collection = array())
	{
		if (!empty($collection)) {
			foreach ($collection as $data) {
				$this->add($data);
			}
		}
	}

	/**
	 * Prepares breadcrumbs
	 * 
	 * @param array $collection Array of breadcrumb instances
	 * @return array
	 */
	private function prepare(array $collection)
	{
		// Iteration count
		$count = 0;

		// Amount of breadcrumbs in provided collection
		$size = count($collection);

		foreach ($collection as $breadcrumb) {
			// If this is fist iteration, then a breadcrumb itself must be considered as first
			if ($count == 0) {
				$breadcrumb->setFirst(true);
			} else {
				// The rest is never first accordingly
				$breadcrumb->setFirst(false);
			}

			// After we're doing dealing with first, it's time to increment the counter
			++$count;

			if ($count == $size) {
				$breadcrumb->setActive(true);
			} else {
				$breadcrumb->setActive(false);
			}
		}

		return $collection;
	}

	/**
	 * Removes first breadcrumb
	 * 
	 * @return \Krystal\Form\Navigation\BreadcrumbBag
	 */
	public function removeFirst()
	{
		array_pop($this->collection);
		return $this;
	}

	/**
	 * Adds breadcrumb collection
	 * 
	 * @param array $collection
	 * @return void
	 */
	public function add(array $collection)
	{
		foreach ($collection as $data) {
			$breadcrumb = new Breadcrumb();
			$breadcrumb->setName($data['name'])
					   ->setLink(isset($data['link']) ? $data['link'] : '#');
			
			array_push($this->collection, $breadcrumb);
		}
	}

	/**
	 * Checks whether breadcrumb bag is empty
	 * 
	 * @return boolean
	 */
	public function has()
	{
		return !empty($this->collection);
	}

	/**
	 * Clears the collection
	 * 
	 * @return void
	 */
	public function clear()
	{
		$this->collection = array();
	}

	/**
	 * Returns breadcrumb collection
	 * 
	 * @return array
	 */
	public function getBreadcrumbs()
	{
		return $this->prepare($this->collection);
	}
}

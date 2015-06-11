<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Providers;

class DataSorter extends AbstractProvider
{
	/**
	 * Returns sorting options
	 * 
	 * @return array
	 */
	final public function getSortingOptions()
	{
		return $this->getAllPrepared();
	}

	/**
	 * Returns current sort option
	 * 
	 * @return string
	 */
	final public function getSortOption()
	{
		return $this->getData();
	}

	/**
	 * Stores sorting option
	 * 
	 * @param string $sort
	 * @return boolean
	 */
	final public function setSortOption($sort)
	{
		return $this->setData($sort);
	}
}

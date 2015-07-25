<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Filter;

interface QueryContainerInterface
{
	/**
	 * Checks whether a filter has been applied
	 * 
	 * @return boolean
	 */
	public function isApplied();

	/**
	 * Returns key's value if exists
	 * 
	 * @param string $key
	 * @return string
	 */
	public function get($key);
}

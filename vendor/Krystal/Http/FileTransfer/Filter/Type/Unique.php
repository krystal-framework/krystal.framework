<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\FileTransfer\Filter\Type;

final class Unique implements FilterTypeInterface
{
	/**
	 * Filters file's base name
	 * 
	 * @param string $name
	 * @return string
	 */
	public function filter($name)
	{
		return sprintf('%s.%s', uniqid(false, false), pathinfo($name, \PATHINFO_EXTENSION));
	}
}

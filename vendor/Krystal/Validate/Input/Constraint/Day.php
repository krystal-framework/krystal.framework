<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Input\Constraint;

/**
 * Checks whether given string is in a range between 1 and 31
 */
final class Day extends AbstractConstraint
{
	/**
	 * {@inheritDoc}
	 */
	protected $message = 'Given string is out of day range';

	/**
	 * {@inheritDoc}
	 */
	public function isValid($target)
	{
		if ($target > 31) {

			$this->violate($this->message);
			return false;

		} else {
			return true;
		}
	}
}

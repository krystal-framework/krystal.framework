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
 * Checks whether given string is a callback function
 */
final class Callable extends AbstractConstraint
{
	/**
	 * {@inheritDoc}
	 */
	protected $message = 'Given string is not callable';

	/**
	 * {@inheritDoc}
	 */
	public function isValid($target)
	{
		if (is_callable($target)) {
			return true;

		} else {

			$this->violate($this->message);
			return false;
		}
	}
}

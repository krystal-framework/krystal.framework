<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Constraint;

/**
 * Checks whether RegEx is valid
 */
final class RegEx extends AbstractConstraint
{
	/**
	 * {@inheritDoc}
	 */
	protected $message = 'Given string is not a valid RegEx';

	/**
	 * {@inheritDoc}
	 */
	public function isValid($target)
	{
		// @ - intentionally
		if (@preg_match($target, ' ')) {
			return true;
		} else {

			$this->violate($this->message);
			return false;
		}
	}
}

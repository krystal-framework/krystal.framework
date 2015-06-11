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
 * Checks whether email pattern is valid
 */
final class EmailPattern extends AbstractConstraint
{
	/**
	 * {@inheritDoc}
	 */
	protected $message = 'Email does not seem to be valid';

	/**
	 * {@inheritDoc}
	 */
	public function isValid($target)
	{
		if (filter_var($target, \FILTER_VALIDATE_EMAIL)) {
			return true;
			
		} else {
			$this->violate($this->message);
			return false;
		}
	}
}

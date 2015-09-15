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

final class Empty extends AbstractConstraint
{
	/**
	 * {@inheritDoc}
	 */
	protected $message = 'A value should be empty';

	/**
	 * {@inheritDoc}
	 */
	public function isValid($target)
	{
		if (empty($target)) {
			return true;
		} else {
			$this->violate($this->message);
			return false;
		}
	}
}

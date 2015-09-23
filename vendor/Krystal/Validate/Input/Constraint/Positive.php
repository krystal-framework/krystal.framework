<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Input\Constraint;

final class Positive extends AbstractConstraint
{
	/**
	 * {@inheritDoc}
	 */
	protected $message = 'A number must be positive';

	/**
	 * {@inheritDoc}
	 */
	public function isValid($target)
	{
		if ($target > 0) {
			return true;
		} else {
			$this->violate($this->message);
			return false;
		}
	}
}

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

use DateTime;

final class DateFormat extends AbstractConstraint
{
	/**
	 * {@inheritDoc}
	 */
	protected $message = 'Invalid date format supplied';

	/**
	 * {@inheritDoc}
	 */
	public function isValid($format)
	{
		if (DateTime::createFromFormat($format) !== false) {
			return true;
		} else {
			$this->violate($this->message);
			return false;
		}
	}
}

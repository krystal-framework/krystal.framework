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

final class UrlPattern extends AbstractConstraint
{
	/**
	 * {@inheritDoc}
	 */
	protected $message = 'Given string does not look like a valid URL';

	/**
	 * {@inheritDoc}
	 */
	public function isValid($target)
	{
		if (filter_var($target, \FILTER_VALIDATE_URL, \FILTER_FLAG_PATH_REQUIRED & \FILTER_FLAG_QUERY_REQUIRED)) {
			return true;
		} else {
			$this->violate($this->message);
			return false;
		}
	}
}

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

final class MacAddress extends AbstractConstraint
{
	/**
	 * {@inheritDoc}
	 */
	protected $message = 'A value does not look like a MAC-Address';

	/**
	 * {@inheritDoc}
	 */
	public function isValid($target)
	{
		$pattern = '^([0-9A-F]{2}[:-]){5}([0-9A-F]{2})$^';

		if (preg_match($pattern, $target)) {
			return true;
		} else {

			$this->violate($this->message);
			return false;
		}
	}
}

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

use Krystal\Validate\Constraint\AbstractConstraint;

final class Timestamp extends AbstractConstraint
{
	/**
	 * {@inheritDoc}
	 */
	public function isValid($target)
	{
		if (is_numeric($target) && strlen($target) < 11) {
			
			return true;
		} else {
			
			$this->setError($this->message);
			return false;
		}
	}
}


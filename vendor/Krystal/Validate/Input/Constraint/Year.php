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

final class Year extends AbstractConstraint
{
	/**
	 * {@inheritDoc}
	 */
	private $error = 'Given string does not look like a year';
	
	/**
	 * {@inheritDoc}
	 */
	public function isValid($target)
	{
		if (is_numeric($target) && strlen($target) == 4) {
			return true;
			
		} else {
			$this->setError($this->error);
			return false;
		}
	}
}

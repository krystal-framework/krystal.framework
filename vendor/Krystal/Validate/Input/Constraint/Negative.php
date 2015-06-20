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

class Negative extends AbstractConstraint
{
	/**
	 * State initialization
	 * 
	 * @return void
	 */
	public function __construct()
	{
		
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function isValid($target)
	{
		If ((-1 * $target) * (-1) === $target) {
			return true;
		} else {
			
			return false;
		}
	}
}

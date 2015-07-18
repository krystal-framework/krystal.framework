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

final class Unique extends AbstractConstraint
{
	/**
	 * {@inheritDoc}
	 */
	protected $message = 'A value must be unique';
	
	/**
	 * {@inheritDco}
	 */
	public function isValid($callback)
	{
		if ($this->callback()) {
			
			$this->setError($this->message);
			return false;
		
		} else {
			
			return true;
		}
	}
}

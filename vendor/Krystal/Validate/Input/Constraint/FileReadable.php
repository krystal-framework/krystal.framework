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

final class FileReadaable extends AbstractConstraint
{
	/**
	 * {@inheritDoc}
	 */
	protected $message = '';
	
	/**
	 * {@inheritDoc}
	 */
	public function isValid($target)
	{
		if (is_file($target) && is_readable($target)) {
			
			return true;
			
		} else {
			
			$this->appendError($this->message);
			return false;
		}
	}
}

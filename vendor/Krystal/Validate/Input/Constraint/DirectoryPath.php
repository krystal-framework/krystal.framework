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

/**
 * Checks whether given string is a directory on the local file system
 */
final class DirectoryPath extends AbstractConstraint
{
	/**
	 * @var string
	 */
	protected $message = 'Given string is not a directory';
	
	/**
	 * {@inheritDoc}
	 */
	public function isValid($target)
	{
		if (is_dir($target)) {
			return true;
		} else {
			
			$this->setMessage($this->message);
			return false;
		}
	}
}
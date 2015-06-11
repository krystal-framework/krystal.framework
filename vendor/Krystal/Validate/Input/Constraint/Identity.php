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

final class Identity extends AbstractConstraint
{
	/**
	 * {@inheritDoc}
	 */
	protected $message = 'A value is not equal';

	/**
	 * State initialization
	 * 
	 * @param mixed $value
	 * @return void
	 */
	public function __construct($value)
	{
		$this->value = $value;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isValid($target)
	{
		if ($target === $this->value) {
			return true;
			
		} else {
			
			$this->violate($this->message);
			return false;
		}
	}
}

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

final class GreaterThan extends AbstractConstraint
{
	/**
	 * @var int
	 */
	private $value;
	
	/**
	 * {@inheritDoc}
	 */
	protected $message = 'A value must be greater';
	
	/**
	 * State initialization
	 * 
	 * @param string|int $value
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
		if ($target > $this->value) {
			return true;
		} else {
			
			$this->setMessage($this->message);
			return false;
		}
	}
}

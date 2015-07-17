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

final class LessOrEqual extends AbstractConstraint
{
	/**
	 * Target value
	 */
	private $value;

	/**
	 * {@inheritDoc}
	 */
	protected $message = 'Value must be less or equal';

	/**
	 * State initialization
	 * 
	 * @param string|int $value
	 * @return void
	 */
	public function __construct($value)
	{
		$this->value = (int) $value;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function isValid($target)
	{
		if ($target >= $this->value) {
			return true;

		} else {

			$this->violate($this->message);
			return false;
		}
	}
}

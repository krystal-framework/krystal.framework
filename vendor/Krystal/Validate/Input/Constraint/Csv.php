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
 * Checks if comma-separated values are properly formed
 */
final class Csv extends AbstractConstraint
{
	/**
	 * @var string
	 */
	private $separator;

	/**
	 * State initialization
	 * 
	 * @return void
	 */
	public function __construct($separator = ',')
	{
		$this->separator = $separator;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isValid($taregt)
	{
		$values = explode($this->separator, $target);
		
		foreach ($values as $value) {
			//@TODO
		}
	}
}

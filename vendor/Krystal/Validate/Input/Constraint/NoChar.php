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
 * Checks whether a string does not contain a character
 */
final class NoChar extends AbstractConstraint
{
	/**
	 * @var array
	 */
	private $chars = array();

	/**
	 * State initialization
	 * 
	 * @param string|array $chars
	 * @return void
	 */
	public function __construct($chars)
	{
		if (is_array($chars)) {
			$char = (array) $chars;
		}
		
		$this->char = $char;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function isValid($target)
	{
		
	}
}
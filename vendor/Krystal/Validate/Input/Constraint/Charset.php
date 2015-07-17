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
 * Checks whether given string in particular charset
 */
final class Charset extends AbstractConstraint
{
	/**
	 * The target charset
	 * 
	 * @var string
	 */
	private $charset;

	/**
	 * State initialization
	 * 
	 * @param string $charset
	 * @return void
	 */
	public function __construct($charset)
	{
		$this->charset = $charset;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isValid($target)
	{
		//todo
	}
}

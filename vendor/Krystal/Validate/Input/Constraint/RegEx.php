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
 * Checks whether regex is valid
 */
final class RegEx extends AbstractConstraint
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
	}
}

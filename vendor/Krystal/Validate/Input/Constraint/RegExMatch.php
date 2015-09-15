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

/* Validates a regular expression against a string */
final class RegExMatch extends AbstractConstraint
{
	/**
	 * Target RegEx
	 * 
	 * @var string
	 */
	private $regex;

	/**
	 * {@inheiritDoc}
	 */
	protected $message = 'Defined regular expression does not match a target string';

	/**
	 * State initialization
	 * 
	 * @param string $regex
	 * @return void
	 */
	public function __construct($regex)
	{
		$this->regex = $regex;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isValid($target)
	{
		// @ - intentionally since a regular expression might malformed
		if (@preg_match($this->regex, $target)) {
			return true;
		} else {
			$this->violate($this->message);
			return false;
		}
	}
}

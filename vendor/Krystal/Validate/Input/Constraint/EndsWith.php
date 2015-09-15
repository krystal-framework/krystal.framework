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

final class EndsWith extends AbstractConstraint
{
	/**
	 * A character that needs to be found
	 * 
	 * @var string
	 */
	private $needle;

	/**
	 * {@inheritDoc}
	 */
	protected $message = 'Given string does not end with a required character';

	/**
	 * State initialization
	 * 
	 * @param string $needle
	 * @return void
	 */
	public function __construct($needle)
	{
		$this->needle = $needle;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isValid($haystack)
	{
		if ($this->needle === '' || substr_compare($haystack, $this->needle, -strlen($this->needle)) === 0) {
			return true;
		} else {
			$this->violate($this->message);
			return false;
		}
	}
}

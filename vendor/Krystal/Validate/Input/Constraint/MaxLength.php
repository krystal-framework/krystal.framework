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

final class MaxLength extends AbstractConstraint
{
	/**
	 * {@inheritDoc}
	 */
	protected $message = 'A value should exceeds its maximal length';

	/**
	 * Target maximal length we're comparing against
	 * 
	 * @var integer
	 */
	private $length;

	/**
	 * State initialization
	 * 
	 * @param integer $length
	 * @return void
	 */
	public function __construct($length)
	{
		$this->length = $length;
	}

	/**
	 * Checks whether target is valid
	 * 
	 * @param string $target
	 * @return boolean True if string is blank, False otherwise
	 */
	public function isValid($target)
	{
		if (mb_strlen($target, $this->charset) > $this->length) {
			$this->violate($this->message);
			return false;
		} else {
			return true;
		}
	}
}

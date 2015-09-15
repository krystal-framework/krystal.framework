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

/* Validates date string against date formats (or a single format) */
final class DateFormatMatch extends AbstractConstraint
{
	/**
	 * Supplied date formats
	 * 
	 * @var array
	 */
	private $formats;

	/**
	 * {@inheritDoc}
	 */
	protected $message = 'Date format mismatch';

	/**
	 * State initialization
	 * 
	 * @param mixed $formats
	 * @return void
	 */
	public function __construct($formats)
	{
		if (!is_array($formats)) {
			$formats = array($formats);
		}

		$this->formats = $formats;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isValid($date)
	{
		if ($this->isValidFormat($date)) {
			return true;
		} else {
			$this->violate($this->message);
			return false;
		}
	}

	/**
	 * Validates date string against known formats
	 * 
	 * @param string $data
	 * @return boolean
	 */
	private function isValidFormat($date)
	{
		foreach ($this->formats as $format) {
			if (date($format, strtotime($date)) == $date) {
				return true;
			}
		}

		return false;
	}
}

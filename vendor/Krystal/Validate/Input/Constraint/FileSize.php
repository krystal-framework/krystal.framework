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

final class FileSize extends AbstractConstraint
{
	/**
	 * {@inheritDoc}
	 */
	protected $message = 'Given file path does not match required size';

	/**
	 * Desired file size in bytes
	 * 
	 * @var integer
	 */
	private $size;

	/**
	 * State initialization
	 * 
	 * @param string $size
	 * @return void
	 */
	public function __construct($size)
	{
		$this->size = (int) $size;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isValid($target)
	{
		if (filesize($target) == $this->size) {
			return true;

		} else {

			$this->violate($this->message);
			return false;
		}
	}
}

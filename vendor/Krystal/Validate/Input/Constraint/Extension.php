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

/**
 * If a path contains required extension
 */
final class Extension extends AbstractConstraint
{
	/**
	 * {@inheritDoc}
	 */
	protected $message = 'Given path does not contain required extension';

	/**
	 * Desired extension
	 * 
	 * @var string
	 */
	private $extension;

	/**
	 * State initialization
	 * 
	 * @param string $extension
	 * @return void
	 */
	public function __construct($extension)
	{
		$this->extension = $extension;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isValid($target)
	{
		if ($this->extension == pathinfo($target, \PATHINFO_EXTENSION)) {
			return true;
		} else {

			$this->violate($this->message);
			return false;
		}
	}
}

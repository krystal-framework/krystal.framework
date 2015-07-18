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

/**
 * Checks whether a string is serialized
 */
final class Serialized extends AbstractConstraint
{
	/**
	 * {@inheritDoc}
	 */
	private $adapter;

	/**
	 * {@inheritDoc}
	 */
	protected $message = 'Given string is not serialized';

	/**
	 * State initialization
	 * 
	 * @param object $adapter
	 * @return void
	 */
	public function __construct($adapter)
	{
		$this->adapter = $adapter;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isValid($target)
	{
		if ($this->adapter->isSerialized($target)) {
			return true;

		} else {

			$this->violate($this->message);
			return false;
		}
	}
}

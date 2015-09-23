<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Input\Constraint;

/**
 * Checks whether a string does not contain a character
 */
final class NoChar extends AbstractConstraint
{
	/**
	 * Collection of forbidden characters
	 * 
	 * @var array
	 */
	private $chars = array();

	/**
	 * {@inheritDoc}
	 */
	protected $message = 'Given value should not contain forbidden character';

	/**
	 * State initialization
	 * 
	 * @param string|array $chars
	 * @return void
	 */
	public function __construct($chars)
	{
		if (!is_array($chars)) {
			$chars = array($chars);
		}

		$this->chars = $chars;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isValid($target)
	{
		foreach ($this->chars as $char) {
			if (strpos($target, $char) !== false) {

				$this->vioate($this->message);
				return false;
			}
		}

		return true;
	}
}

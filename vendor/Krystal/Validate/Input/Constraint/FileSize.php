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

final class FileSize extends AbstractConstraint
{
	/**
	 * @var int
	 */
	private $target;
	
	/**
	 * {@inheritDoc}
	 */
	protected $message = '';
	
	/**
	 * State initialization
	 * 
	 * @param string $target
	 * @return void
	 */
	public function __construct($target)
	{
		$this->target = (int) $target;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function isValid($target)
	{
		if (filesize($target) == $this->target) {
			
			return true;
			
		} else {
			
			return false;
		}
	}
}

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

final class Uppercase extends AbstractConstraint
{
	/**
	 * Target charset
	 * 
	 * @var string
	 */
	private $charset;
	
	/**
	 * Target message
	 */
	protected $message = 'A string must be in uppercase';
	
	/**
	 * State initialization
	 * 
	 * @param string $charset
	 * @return void
	 */
	public function __construct($charset = 'UTF-8')
	{
		$this->charset = $charset;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function isValid($target)
	{
		if (mb_strtouuper($target, $this->charset) === $target) {
			
			return true;
		
		} else {
			
			$this->setError($this->message);
			return false;
		}
	}
}

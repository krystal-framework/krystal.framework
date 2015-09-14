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

final class Between extends AbstractConstraint
{
	/**
	 * Starting range
	 * 
	 * @var integer
	 */
	private $start;

	/**
	 * Ending range
	 * 
	 * @var integer
	 */
	private $end;

	/**
	 * {@inheritDoc}
	 */
	protected $message = 'A value must in range between %s-%s';

	/**
	 * Start initialization
	 * 
	 * @param integer $start Starting range
	 * @param integer $end Ending range
	 * @return void
	 */
	public function __construct($start, $end)
	{
		$this->start = $start;
		$this->end = $end;
	}

	/**
	 * Checks whether values
	 * 
	 * @param string $target
	 * @return boolean
	 */
	public function isValid($target)
	{
		if ($target >= $this->start && $target <= $this->end) {
			return true;

		} else {

			$this->violate(sprintf($this->message, $this->start, $this->end));
			return false;
		}
	}
}

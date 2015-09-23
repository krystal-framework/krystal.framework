<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Captcha\Standard\Text;

abstract class AbstractGenerator
{
	/**
	 * Target answer
	 * 
	 * @var string
	 */
	protected $answer;

	/**
	 * Returns an answer
	 * 
	 * @return string
	 */
	final public function getAnswer()
	{
		return $this->answer;
	}

	/**
	 * Defines a answer
	 * 
	 * @param string $answer
	 * @return void
	 */
	final public function setAnswer($answer)
	{
		$this->answer = $answer;
	}

	/**
	 * Generates a random string and saves it as an answer into the memory
	 * 
	 * @return string
	 */
	abstract public function generate();
}

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

final class RandomNumber extends AbstractGenerator
{
	/**
	 * Maximal length of the text to be generated
	 * 
	 * @var integer
	 */
	private $length;

	/**
	 * State initialization
	 * 
	 * @param integer $length Maximal string length to be generated
	 * @return void
	 */
	public function __construct($length = 6)
	{
		$this->length = $length;
	}

	/**
	 * {@inheritDoc}
	 */
	public function generate()
	{
		$text = mt_rand();

		$text = substr($text, 0, $this->length);
		$this->setAnswer($text);

		return $text;
	}
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Text;

final class TextTrimmer implements TextTrimmerInterface
{
	/**
	 * Content to be appended after trimming is done
	 * 
	 * @var string
	 */
	private $after;

	/**
	 * State initialization
	 * 
	 * @param string $after Content to be appended after
	 * @return void
	 */
	public function __construct($after = ' .... ')
	{
		$this->after = $after;
	}

	/**
	 * Trims the text
	 * 
	 * @param string $content
	 * @param integer $maxLen Maximal allowed length
	 * @return string
	 */
	public function trim($content, $maxLen)
	{
		return mb_substr($content, 0, $maxLen, 'UTF-8') . $this->after;
	}
}

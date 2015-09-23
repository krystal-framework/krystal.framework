<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Text;

interface TextTrimmerInterface
{
	/**
	 * Trims the text
	 * 
	 * @param string $content
	 * @param integer $maxLen Maximal allowed length
	 * @return string
	 */
	public function trim($content, $maxLen);
}

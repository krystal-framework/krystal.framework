<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Security;

class Filter
{
	/**
	 * Escapes special HTML values
	 * 
	 * @param string $value
	 * @return string
	 */
	public static function escape($value)
	{
		return htmlentities($value, \ENT_QUOTES, 'UTF-8');
	}

	/**
	 * Escapes HTML content
	 * 
	 * @param string $content
	 * @return string
	 */
	public static function escapeContent($content)
	{
		return $content;
	}
}

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
	 * Determines whether a string has HTML tags
	 * 
	 * @param string $target
	 * @param array $exceptions Tags to be ignored
	 * @return boolean
	 */
	public static function hasTags($target, array $exceptions = array())
	{
		return self::stripTags($target, $exceptions) !== $target;
	}

	/**
	 * Strip the tags, even malformed ones
	 * 
	 * @param string $text Target HTML string
	 * @param array $allowed An array of allowed tags
	 * @return string
	 */
	public static function stripTags($text, array $allowed = array())
	{
		// Based on [fernando at zauber dot es]'s solution
		$allowed = array_map('strtolower', $allowed);

		return preg_replace_callback('/<\/?([^>\s]+)[^>]*>/i', function ($matches) use (&$allowed) {
			return in_array(strtolower($matches[1]), $allowed) ? $matches[0] : '';
		}, $text);
	}

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

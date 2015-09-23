<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form;

class HtmlHelper
{
	/**
	 * Returns property in case a condition is true
	 * 
	 * @param boolean $condition
	 * @param string $property
	 * @return string
	 */
	private static function getOnDemand($condition, $property)
	{
		if ($condition == true) {
			return sprintf(' %s ', $property);
		}

		return null;
	}

	/**
	 * Wraps content into a tag on demand
	 * 
	 * @param boolean $condition
	 * @param string $tag
	 * @param string $content
	 * @return void
	 */
	public static function wrapOnDemand($condition, $tag, $content)
	{
		if ($condition) {
			echo sprintf('<%s>%s</%s>', $tag, $content, $tag);
		} else {
			echo $content;
		}
	}

	/**
	 * Prints "readony" property on demand
	 * 
	 * @param boolean $condition
	 * @return void
	 */
	public static function makeReadOnlyOnDemand($condition)
	{
		echo self::getOnDemand($condition, 'readonly');
	}

	/**
	 * Prints selected property on demand
	 * 
	 * @param boolean $condition
	 * @return void
	 */
	public static function selectOnDemand($condition)
	{
		echo self::getOnDemand($condition, 'selected');
	}

	/**
	 * Checks on demand
	 * 
	 * @param string $condition
	 * @return void
	 */
	public static function checkOnDemand($condition)
	{
		echo self::getOnDemand($condition, 'checked');
	}

	/**
	 * Adds raw text in case $condition is true
	 * 
	 * @param boolean $condition
	 * @param string $text
	 * @return void
	 */
	public static function addOnDemand($condition, $text)
	{
		if ((bool) $condition) {
			echo $text;
		}
	}

	/**
	 * Prints an attribute on demand
	 * 
	 * @param boolean $condition
	 * @param string $attr
	 * @param string $value
	 * @return void
	 */
	public static function addAttrOnDemand($condition, $attr, $value)
	{
		if ($condition) {
			echo sprintf(' %s="%s" ', $attr, $value);
		}
	}

	/**
	 * Prints a class on demand
	 * 
	 * @param boolean $condition
	 * @param string $value
	 * @return void
	 */
	public static function addClassOnDemand($condition, $value)
	{
		self::addAttrOnDemand($condition, 'class', $value);
	}
}

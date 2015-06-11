<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form;

final class HtmlHelper
{
	/**
	 * Returns $property
	 * 
	 * @return void
	 */
	private function getOnDemand($condition, $property)
	{
		if ($condition == true) {
			return sprintf(' %s ', $property);
		}
	}

	/**
	 * Wraps content into a tag on demand
	 * 
	 * @return void
	 */
	public function wrapOnDemand($condition, $tag, $content)
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
	public function makeReadOnlyOnDemand($condition)
	{
		echo $this->getOnDemand($condition, 'readonly');
	}

	/**
	 * Prints selected property on demand
	 * 
	 * @param boolean $condition
	 * @return void
	 */
	public function selectOnDemand($condition)
	{
		echo $this->getOnDemand($condition, 'selected');
	}

	/**
	 * Checks on demand
	 * 
	 * @param string $condition
	 * @return void
	 */
	public function checkOnDemand($condition)
	{
		echo $this->getOnDemand($condition, 'checked');
	}

	/**
	 * Adds an attribute on demand
	 * 
	 * @param boolean $condition
	 * @param string $attr
	 * @param string $value
	 * @return string
	 */
	private function getAttrOnDemand($condition, $attr, $value)
	{
		if ($condition) {
			return sprintf(' %s="%s" ', $attr, $value);
		}
	}

	/**
	 * @return void
	 */
	public function addOnDemand($condition, $value)
	{
		if ((bool) $condition) {
			echo $value;
		}
	}

	/**
	 * Prints a class on demand
	 * 
	 * @param boolean $condition
	 * @param string $value
	 * @return void
	 */
	public function addClassOnDemand($condition, $value)
	{
		echo $this->getAttrOnDemand($condition, 'class', $value);
	}
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Widget;

use Krystal\Form\Element\Select;

class DropdownWidget
{
	/**
	 * Renders a drop-down list
	 * 
	 * @param array $attrs Element attributes
	 * @param array $list Data
	 * @param string $active Option's element to be marked as selected
	 * @param string $prompt Default empty value at the top. If false, then its disabled
	 * @return string
	 */
	public static function render(array $attrs, array $list, $active, $prompt = '')
	{
		if ($prompt !== false) {
			$defaults = array('' => $prompt);
		} else {
			$defaults = array();
		}

		$element = new Select($list, $active, $defaults);
		return $element->render($attrs);
	}
}

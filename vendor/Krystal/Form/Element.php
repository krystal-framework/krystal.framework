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

class Element
{
	/**
	 * Creates text input element
	 * 
	 * @return \Krystal\Form\NodeElement
	 */
	public static function createText()
	{
		$element = new NodeElement();
		$element->openTag('input')
				->setType('text');

		return $element;
	}
}
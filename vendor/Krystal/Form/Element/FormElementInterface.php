<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Element;

interface FormElementInterface
{
	/**
	 * Renders an element
	 * 
	 * @param array $attrs Element attributes
	 * @return string
	 */
	public function render(array $attrs);
}

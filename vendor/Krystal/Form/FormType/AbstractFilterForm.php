<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\FormType;

abstract class AbstractFilterForm extends AbstractForm
{
	/**
	 * Renders a form element
	 * 
	 * @param string $name Element's name to be rendered
	 * @return string
	 */
	public function render($element)
	{
		// Lazy form registration
		if ($this->registered !== true) {
			$this->register();
		}

		return $this->stack[$element];
	}
}

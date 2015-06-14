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

final class TableForm extends AbstractForm
{
	private $value;
	
	protected function register()
	{
		foreach ($this->getElements() as $name => $options) {
			if (isset($options['element']['type'])) {

				$element = $this->renderElement(sprintf('%s[%s]', $name, $this->value), $options);

				// The call returns null if can not register
				if ($element !== null) {
					// Do register now, since its safe
					$this->stack[$name] = $element;
				}
			}
		}

		$this->registered = true;
	}
	
	/**
	 * Renders an element providing custom name
	 * 
	 * @return string
	 */
	public function render($element, $value)
	{
		$this->value = $value;
		
		$this->register();

		return $this->stack[$element];
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getAttrs(array $options)
	{
		return $options;
	}
}

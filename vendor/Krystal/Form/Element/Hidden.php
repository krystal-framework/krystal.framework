<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Element;

use Krystal\Form\NodeElement;

class Hidden
{
	/**
	 * Builds an instance
	 * 
	 * @param $input
	 * @param string $name
	 * @param array $options
	 * @return \Krystal\Form\Element\Hidden
	 */
	public static function factory($input, $name, array $options)
	{
		$element = new self();

		// Guess a name
		$options['element']['attributes']['name'] = $input->guessName($name);

		if ($input->has($name)) {
			$options['element']['attributes']['value'] = $input->get($name);
		}

		return $element->render($options['element']['attributes']);
	}

	/**
	 * {@inheritDoc}
	 */
	public function render(array $attrs)
	{
		// Default hidden-specific attributes
		$defaults = array(
			'type' => 'hidden',
			//'value' => '0'
		);

		// Now allowing to override the type
		if (isset($attrs['type'])) {
			unset($attrs['type']);
		}

		// Finally merge defaults with user-defined attributes
		$attrs = array_merge($attrs, $defaults);

		$hidden = new NodeElement();
		$hidden->openTag('input')
			   ->addAttributes($attrs)
			   ->finalize(true);

		return $hidden->render();
	}
}

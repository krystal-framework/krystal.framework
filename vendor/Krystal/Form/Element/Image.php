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
use Krystal\Form\InputInterface;

final class Image implements FormElementInterface
{
	/**
	 * Builds an element
	 * 
	 * @param \Krystal\Form\InputInterface $input
	 * @param string $name
	 * @param array $options
	 * @return \Krystal\Form\Element\Text
	 */
	public static function factory(InputInterface $input, $name, array $options)
	{
		$element = new self();

		// Guess a name
		$options['element']['attributes']['name'] = $input->guessName($name);

		return $element->render($options['element']['attributes']);
	}

	/**
	 * {@inheritDoc}
	 */
	public function render(array $attrs)
	{
		$node = new NodeElement();

		return $node->openTag('img')
					->addAttributes($attrs)
					->finalize(true)
					->render();
	}
}

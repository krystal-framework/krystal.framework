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

final class Textarea implements FormElementInterface
{
	/**
	 * Current content
	 * 
	 * @var string
	 */
	private $text;

	/**
	 * State initialization
	 * 
	 * @param string $text
	 * @return void
	 */
	public function __construct($text = null)
	{
		$this->text = $text;
	}

	/**
	 * Builds the element
	 * 
	 * @param \Krystal\Form\InputInterface $input
	 * @param string $name
	 * @param array $options
	 * @return \Krystal\Form\Element\Textarea
	 */
	public static function factory(InputInterface $input, $name, array $options)
	{
		$text = '';

		// Guess a name
		$options['element']['attributes']['name'] = $input->guessName($name);

		if ($input->has($name)) {
			$text = $input->get($name);
		}

		$element = new self($text);

		return $element->render($options['element']['attributes']);
	}

	/**
	 * {@inheritDoc}
	 */
	public function render(array $attrs)
	{
		$node = new NodeElement();
		$node->openTag('textarea')
		     ->addAttributes($attrs)
			 ->finalize()
			 ->setText($this->text)
			 ->closeTag();
			 
		return $node->render();
	}
}

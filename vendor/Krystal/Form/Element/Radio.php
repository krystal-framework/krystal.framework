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

final class Radio implements FormElementInterface
{
	/**
	 * Whether must be checked on rendering or not
	 * 
	 * @var boolean
	 */
	private $active;

	/**
	 * State initialization
	 * 
	 * @param boolean $active
	 * @return void
	 */
	public function __construct($active)
	{
		$this->active = $active;
	}

	/**
	 * Builds radio element
	 * 
	 * @param \Krystal\Form\Element\InputInterface $input
	 * @param string $name
	 * @param array $options
	 * @return \Krystal\Form\Element\Radio
	 */
	public function factory(InputInterface $input, $name, array $options)
	{
		$element = new self();

		// If a name isn't set explicitly, then guess it
		if (!isset($options['element']['attributes']['name'])) {
			$options['element']['attributes']['name'] = $input->guessName($name);
		}

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
		$attrs['type'] = 'radio';

		$node = new NodeElement();
		$node->openTag('input')
			 ->addAttributes($attrs);

		// Check if active
		if ($this->active) {
			$node->addProperty('checked');
		}

		return $node->finalize(true)
					->render();
	}
}

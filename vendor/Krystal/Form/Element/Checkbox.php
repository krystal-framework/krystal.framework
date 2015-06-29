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

final class Checkbox implements FormElementInterface
{
	/**
	 * Whether to serialize a checkbox
	 * 
	 * @var boolean
	 */
	private $serialize;

	/**
	 * Active value
	 * 
	 * @var string
	 */
	private $active;

	/**
	 * State initialization
	 * 
	 * @param boolean $serialize
	 * @param string $active
	 * @return void
	 */
	public function __construct($serialize, $active)
	{
		$this->serialize = $serialize;
		$this->active = $active;
	}

	/**
	 * Builds an element
	 * 
	 * @param \Krystal\Form\InputInterface $input
	 * @param string $name
	 * @param array $options
	 * @return \Krystal\Form\Element\Checkbox
	 */
	public static function factory(InputInterface $input, $name, array $options)
	{
		// By default, it's not checked
		$checked = false;

		// Pick up active element
		if ($input->has($name)) {
			$checked = $input->get($name);
		}

		// If a name isn't set explicitly, then guess it
		if (!isset($options['element']['attributes']['name'])) {
			$options['element']['attributes']['name'] = $input->guessName($name);
		}

		// Determine if it must be serialize-able
		$serialize = isset($options['element']['serialize']) ? (bool) $options['element']['serialize'] : true;

		$element = new self($serialize, $checked);

		return $element->render($options['element']['attributes']);
	}

	/**
	 * Creates hidden node
	 * 
	 * @param array $attrs
	 * @return \Krystal\Form\NodeElement
	 */
	private function createHiddenNode(array $attrs)
	{
		$attrs = array(
			'type' => 'hidden',
			'name' => $attrs['name'],
			'value' => '0'
		);

		$node = new NodeElement();
		$node->openTag('input')
			 ->addAttributes($attrs)
			 ->finalize(true);

		return $node;
	}

	/**
	 * Creates checkbox node
	 * 
	 * @param array $attrs
	 * @return \Krystal\Form\NodeElement
	 */
	private function createCheckboxNode(array $attrs)
	{
		$defaults = array(
			'type' => 'checkbox',
			'value' => '1'
		);

		$attrs = array_merge($attrs, $defaults);

		$node = new NodeElement();
		$node->openTag('input')
			 ->addAttributes($attrs);

		// Check if active
		if ($this->active == $defaults['value']) {
			$node->addProperty('checked');
		}

		$node->finalize(true);

		return $node;
	}

	/**
	 * {@inheritDoc}
	 */
	public function render(array $attrs)
	{
		if ($this->serialize) {
			$hidden = $this->createHiddenNode($attrs);
		}

		$checkbox = $this->createCheckboxNode($attrs);

		$response = null;

		// If hidden element is created so far
		if (isset($hidden)) {
			$response = $hidden->render();
		}

		$response .= $checkbox->render();

		return $response;
	}
}

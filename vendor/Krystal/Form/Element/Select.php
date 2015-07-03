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

/* @TODO Add visitor for option elements */
final class Select implements FormElementInterface
{
	/**
	 * List data
	 * 
	 * @var array
	 */
	private $data = array();

	/**
	 * Defaults
	 * 
	 * @var array
	 */
	private $defaults = array();

	/**
	 * Active element to be selected
	 * 
	 * @var string
	 */
	private $active;

	/**
	 * State initialization
	 * 
	 * @param array $data
	 * @param string $active
	 * @param array $defaults
	 * @return void
	 */
	public function __construct(array $data, $active, array $defaults)
	{
		$this->data = $data;
		$this->active = $active;
		$this->defaults = $defaults;
	}

	/**
	 * Builds an element
	 * 
	 * @return \Krystal\Form\Element\Select
	 */
	public static function factory()
	{
	}

	/**
	 * Creates default option node
	 * 
	 * @return \Krystal\Form\NodeElement
	 */
	private function createDefaultOptionNodes()
	{
		$nodes = array();

		foreach ($this->defaults as $key => $value) {
			// Only scalars are allowed, the rest is ignored
			if (is_scalar($value)) {
				array_push($nodes, $this->createOptionNode($key, $value));
			}
		}

		return $nodes;
	}

	/**
	 * Creates option node
	 * 
	 * @param string $value
	 * @param string $text
	 * @return \Krystal\Form\NodeElement
	 */
	private function createOptionNode($value, $text)
	{
		$option = new NodeElement();
		$option->openTag('option')
			   ->addAttribute('value', $value);

		// Mark as selected on demand
		if ($value == $this->active) {
			$option->addProperty('selected');
		}

		$option->finalize()
			   ->setText($text)
			   ->closeTag();
		
		return $option;
	}

	/**
	 * Creates optgroup node with nested options
	 * 
	 * @param string $label
	 * @param array $list
	 * @return \Krystal\Form\NodeElement
	 */
	private function createOptgroupNode($label, array $list)
	{
		$optgroup = new NodeElement();
		$optgroup->openTag('optgroup')
				 ->addAttribute('label', $label)
				 ->finalize();

		foreach ($this->getOptions($list) as $option) {
			$optgroup->appendChild($option);
		}

		$optgroup->closeTag();

		return $optgroup;
	}

	/**
	 * Returns a collection of prepared option elements
	 * 
	 * @param array $list
	 * @param string $active Node to be marked as selected
	 * @return array
	 */
	private function getOptions(array $list)
	{
		// To be returned
		$elements = array();

		foreach ($list as $value => $text) {
			array_push($elements, $this->createOptionNode($value, $text));
		}

		return $elements;
	}

	/**
	 * {@inheritDoc}
	 */
	public function render(array $attrs)
	{
		$select = new NodeElement();
		$select->openTag('select')
			   ->addAttributes($attrs)
			   ->finalize();

		// First of all lets look if we have defaults
		if (!empty($this->defaults)) {
			foreach ($this->createDefaultOptionNodes() as $node) {
				$select->appendChild($node);
			}
		}

		foreach ($this->data as $label => $list) {
			if (is_array($list)) {
				$select->appendChild($this->createOptgroupNode($label, $list));
			} else {
				$select->appendChild($this->createOptionNode($label, $list));
			}
		}

		$select->closeTag();

		return $select->render();
	}
}

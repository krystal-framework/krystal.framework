<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form;

final class NodeElement implements NodeElementInterface
{
	/**
	 * Current string
	 * 
	 * @var string
	 */
	private $string;

	/**
	 * Current opened tag name
	 * 
	 * @var string
	 */
	private $tag;

	/**
	 * Tells whether a tag is finalized
	 * 
	 * @var boolean
	 */
	private $finalized = false;

	/**
	 * Checks whether a tag is finalized
	 * 
	 * @return boolean
	 */
	private function isFinalized()
	{
		return $this->finalized;
	}

	/**
	 * Appends to a stack
	 * 
	 * @param string $string String to be appended
	 * @return void
	 */
	private function append($string)
	{
		$this->string .= $string;
	}

	/**
	 * Resets the state
	 * 
	 * @return void
	 */
	public function clear()
	{
		$this->string = '';
		$this->tag = '';
		$this->finalized = false;
	}

	/**
	 * Appends a child
	 * 
	 * @param \Krystal\Form\NodeElement $nodeElement
	 * @return \Krystal\Form\NodeElement
	 */
	public function appendChild(NodeElement $nodeElement)
	{
		$this->setText($nodeElement->render());
		return $this;
	}
	
	/**
	 * Appends another element after
	 * 
	 * @param \Krystal\Form\NodeElement $nodeElement
	 * @return \Krystal\Form\NodeElement
	 */
	public function appendAfter(NodeElement $nodeElement)
	{
		$this->setText($nodeElement->render(), false);
		return $this;
	}

	/**
	 * Sets a text
	 * 
	 * @param string $text
	 * @param boolean $finalize Whether to finalize the tag
	 * @return \Krystal\Form\NodeElement
	 */
	public function setText($text, $finalize = true)
	{
		if ($finalize && !$this->isFinalized()) {
			$this->finalize();
		}
		
		$this->append($text);
		return $this;
	}

	/**
	 * Returns constructed element
	 * 
	 * @return string
	 */
	public function render()
	{
		return $this->string;
	}

	/**
	 * Finalizes the opened tag
	 * 
	 * @param boolean $singular Whether element is singular
	 * @return \Krystal\Form\NodeElement
	 */
	public function finalize($singular = false)
	{
		$this->finalized = true;

		if ($singular === true) {
			$text = ' />';
		} else {
			$text = '>';
		}

		$this->append($text);
		return $this;
	}

	/**
	 * Opens a tag
	 * 
	 * @param string $tagName
	 * @return \Krystal\Form\NodeElement
	 */
	public function openTag($tagName)
	{
		$this->append(sprintf('<%s', $tagName));
		$this->tag = $tagName;

		return $this;
	}

	/**
	 * Closes opened tag
	 * 
	 * @param string $tag
	 * @return \Krystal\Form\NodeElement
	 */
	public function closeTag($tag = null)
	{
		if ($tag === null) {
			$tag = $this->tag;
		}
		
		$this->append(sprintf('</%s>', $tag));
		return $this;
	}

	/**
	 * Adds a property
	 * 
	 * @param string $property
	 * @return \Krystal\Form\NodeElement
	 */
	public function addProperty($property)
	{
		$this->append(sprintf(' %s', $property));
		return $this;
	}

	/**
	 * Adds an attribute
	 * 
	 * @param string $attribute
	 * @param string $value
	 * @return \Krystal\Form\NodeElement
	 */
	public function addAttribute($attribute, $value)
	{
		$this->append(sprintf(' %s="%s"', $attribute, $value));
		return $this;
	}

	/**
	 * Adds attribute collection
	 * 
	 * @param array $attributes
	 * @return \Krystal\Form\NodeElement
	 */
	public function addAttributes(array $attributes)
	{
		foreach ($attributes as $attribute => $value) {
			$this->addAttribute($attribute, $value);
		}
		
		return $this;
	}
}
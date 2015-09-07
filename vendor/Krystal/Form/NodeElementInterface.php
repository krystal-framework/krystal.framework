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

interface NodeElementInterface
{
	/**
	 * Resets the state
	 * 
	 * @return void
	 */
	public function clear();

	/**
	 * Appends a child
	 * 
	 * @param \Krystal\Form\NodeElement $nodeElement
	 * @return \Krystal\Form\NodeElement
	 */
	public function appendChild(NodeElement $nodeElement);

	/**
	 * Appends another element after
	 * 
	 * @param \Krystal\Form\NodeElement $nodeElement
	 * @return \Krystal\Form\NodeElement
	 */
	public function appendAfter(NodeElement $nodeElement);

	/**
	 * Sets a text
	 * 
	 * @param string $text
	 * @param boolean $finalize Whether to finalize the tag
	 * @return \Krystal\Form\NodeElement
	 */
	public function setText($text, $finalize = true);

	/**
	 * Returns constructed element
	 * 
	 * @return string
	 */
	public function render();

	/**
	 * Finalizes the opened tag
	 * 
	 * @param boolean $singular Whether element is singular or not
	 * @return \Krystal\Form\NodeElement
	 */
	public function finalize($singular = false);

	/**
	 * Opens a tag
	 * 
	 * @param string $tagName
	 * @return \Krystal\Form\NodeElement
	 */
	public function openTag($tagName);

	/**
	 * Closes opened tag
	 * 
	 * @param string $tag
	 * @return \Krystal\Form\NodeElement
	 */
	public function closeTag($tag = null);

	/**
	 * Adds a property
	 * 
	 * @param string $property
	 * @return \Krystal\Form\NodeElement
	 */
	public function addProperty($property);

	/**
	 * Adds an attribute
	 * 
	 * @param string $attribute
	 * @param string $value
	 * @return \Krystal\Form\NodeElement
	 */
	public function addAttribute($attribute, $value);

	/**
	 * Adds attribute collection
	 * 
	 * @param array $attributes
	 * @return \Krystal\Form\NodeElement
	 */
	public function addAttributes(array $attributes);
}

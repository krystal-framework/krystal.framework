<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form;

use LogicException;

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
	 * Element attributes
	 * 
	 * @var array
	 */
	private $attributes = array();

	/**
	 * Element properties
	 * 
	 * @var array
	 */
	private $properties = array();

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
	 * @return \Krystal\Form\NodeElement
	 */
	public function clear()
	{
		$this->string = '';
		$this->tag = '';
		$this->finalized = false;

		return $this;
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
		array_push($this->properties, $property);

		return $this;
	}

	/**
	 * Adds a property on demand
	 * 
	 * @param string $property
	 * @param mixed $value
	 * @return \Krystal\Form\NodeElement
	 */
	public function addPropertyOnDemand($property, $value)
	{
		if ((bool) $value === true) {
			$this->addProperty($property);
		}

		return $this;
	}

	/**
	 * Adds many properties at once
	 * 
	 * @param array $properties
	 * @return \Krystal\Form\NodeElement
	 */
	public function addProperties(array $properties)
	{
		foreach ($properties as $property) {
			$this->addProperty($property);
		}

		return $this;
	}

	/**
	 * Checks whether property has been added
	 * 
	 * @param string $property
	 * @return boolean
	 */
	public function hasProperty($property)
	{
		return in_array($property, $this->properties);
	}

	/**
	 * Returns all defined properties
	 * 
	 * @return array
	 */
	public function getProperties()
	{
		return $this->properties;
	}

	/**
	 * Adds an attribute
	 * 
	 * @param string $attribute
	 * @param string $value
	 * @throws \LogicException If trying to set existing attribute
	 * @return \Krystal\Form\NodeElement
	 */
	public function addAttribute($attribute, $value)
	{
		if ($this->hasAttribute($attribute)) {
			throw new LogicException(sprintf('The element already has "%s" attribute', $attribute));
		}

		$this->append(sprintf(' %s="%s"', $attribute, $value));
		$this->attributes[$attribute] = $value;

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

	/**
	 * Returns all defined attributes
	 * 
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * Checks whether attribute is defined
	 * 
	 * @param string $attribute
	 * @return boolean
	 */
	public function hasAttribute($attribute)
	{
		return array_key_exists($attribute, $this->attributes);
	}

	/**
	 * Returns attribute value
	 * 
	 * @param string $attribute
	 * @param mixed $default Default value to be returned in case attribute doesn't exist
	 * @return string
	 */
	public function getAttribute($attribute, $default = false)
	{
		if ($this->hasAttribute($attribute)) {
			return $this->attributes[$attribute];
		} else {
			return $default;
		}
	}

	/**
	 * Adds data-* attribute
	 * 
	 * @param string $data
	 * @param string $value
	 * @return \Krystal\Form\NodeElement
	 */
	public function addData($data, $value)
	{
		$this->addAttribute(sprintf('data-%s', $data), $value);
		return $this;
	}

	/**
	 * Checks whether data-* attribute has been set
	 * 
	 * @param string $data
	 * @return boolean
	 */
	public function hasData($data)
	{
		return $this->hasAttribute(sprintf('data-%s', $data));
	}

	/**
	 * Returns data-* attribute
	 * 
	 * @param string $data
	 * @return string
	 */
	public function getData($data)
	{
		if ($this->hasData($data)) {
			return $this->getAttribute(sprintf('data-%s', $data));
		} else {
			return false;
		}
	}

	/**
	 * Adds "required" property on demand
	 * 
	 * @param boolean $value
	 * @return \Krystal\Form\NodeElement
	 */
	public function setRequired($value)
	{
		$this->addPropertyOnDemand('required', $value);
		return $this;
	}

	/**
	 * Checks whether "required" property has been set
	 * 
	 * @return boolean
	 */
	public function isRequired()
	{
		return $this->hasProperty('required');
	}

	/**
	 * Adds "checked" property on demand
	 * 
	 * @param boolean $value
	 * @return \Krystal\Form\NodeElement
	 */
	public function setChecked($value)
	{
		$this->addPropertyOnDemand('checked', $value);
		return $this;
	}

	/**
	 * Checks whether "checked" property has been set
	 * 
	 * @return boolean
	 */
	public function isChecked()
	{
		return $this->hasProperty('checked');
	}

	/**
	 * Adds "selected" property on demand
	 * 
	 * @param boolean $value
	 * @return \Krystal\Form\NodeElement
	 */
	public function setSelected($value)
	{
		$this->addPropertyOnDemand('selected', $value);
		return $this;
	}

	/**
	 * Checks whether "selected" property has been set
	 * 
	 * @return boolean
	 */
	public function isSelected()
	{
		return $this->hasProperty('selected');
	}

	/**
	 * Adds "min" attribute
	 * 
	 * @param string $value
	 * @return \Krystal\Form\NodeElement
	 */
	public function setMin($value)
	{
		$this->addAttribute('min', $value);
		return $this;
	}

	/**
	 * Returns "min" attribute value if present
	 * 
	 * @return mixed
	 */
	public function getMin()
	{
		return $this->getAttribute('min');
	}

	/**
	 * Checks whether "min" attribute has beet set
	 * 
	 * @return boolean
	 */
	public function hasMin()
	{
		return $this->hasAttribute('min');
	}

	/**
	 * Adds "max" attribute
	 * 
	 * @param string $value
	 * @return \Krystal\Form\NodeElement
	 */
	public function setMax($value)
	{
		$this->addAttribute('max', $value);
		return $this;
	}

	/**
	 * Returns "max" attribute value if present
	 * 
	 * @return mixed
	 */
	public function getMax()
	{
		return $this->getAttribute('max');
	}

	/**
	 * Checks whether "max" attribute has beet set
	 * 
	 * @return boolean
	 */
	public function hasMax()
	{
		return $this->hasAttribute('max');
	}

	/**
	 * Adds "placeholder" attribute
	 * 
	 * @param string $value
	 * @return \Krystal\Form\NodeElement
	 */
	public function setPlaceholder($value)
	{
		$this->addAttribute('placeholder', $value);
		return $this;
	}

	/**
	 * Returns "placeholder" attribute value if present
	 * 
	 * @return mixed
	 */
	public function getPlaceholder()
	{
		return $this->getAttribute('placeholder');
	}

	/**
	 * Checks whether "placeholder" attribute has beet set
	 * 
	 * @return boolean
	 */
	public function hasPlaceholder()
	{
		return $this->hasAttribute('placeholder');
	}

	/**
	 * Adds "value" attribute
	 * 
	 * @param string $value
	 * @return \Krystal\Form\NodeElement
	 */
	public function setValue($value)
	{
		$this->addAttribute('value', $value);
		return $this;
	}

	/**
	 * Returns "value" attribute value if present
	 * 
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->getAttribute('value');
	}

	/**
	 * Checks whether "value" attribute has beet set
	 * 
	 * @return boolean
	 */
	public function hasValue()
	{
		return $this->hasAttribute('value');
	}

	/**
	 * Adds "name" attribute
	 * 
	 * @param string $value
	 * @return \Krystal\Form\NodeElement
	 */
	public function setName($value)
	{
		$this->addAttribute('name', $value);
		return $this;
	}

	/**
	 * Returns "name" attribute value if present
	 * 
	 * @return mixed
	 */
	public function getName()
	{
		return $this->getAttribute('name');
	}

	/**
	 * Checks whether "name" attribute has beet set
	 * 
	 * @return boolean
	 */
	public function hasName()
	{
		return $this->hasAttribute('name');
	}

	/**
	 * Adds "id" attribute
	 * 
	 * @param string $value
	 * @return \Krystal\Form\NodeElement
	 */
	public function setId($value)
	{
		$this->addAttribute('id', $value);
		return $this;
	}

	/**
	 * Returns "id" attribute value if present
	 * 
	 * @return mixed
	 */
	public function getId()
	{
		return $this->getAttribute('id');
	}

	/**
	 * Checks whether "id" attribute has beet set
	 * 
	 * @return boolean
	 */
	public function hasId()
	{
		return $this->hasAttribute('id');
	}

	/**
	 * Adds "class" attribute
	 * 
	 * @param string $class
	 * @return \Krystal\Form\NodeElement
	 */
	public function setClass($class)
	{
		$this->addAttribute('class', $class);
		return $this;
	}

	/**
	 * Returns "class" attribute
	 * 
	 * @return \Krystal\Form\NodeElement
	 */
	public function getClass()
	{
		return $this->getAttribute('class');
	}

	/**
	 * Appends a class
	 * 
	 * @param string $class
	 * @return \Krystal\Form\NodeElement
	 */
	public function addClass($class)
	{
		$new = sprintf('%s %s', $this->getClass(), $class);
		$this->setClass($new);

		return $this;
	}

	/**
	 * Determines whether element has a class
	 * 
	 * @param string $class
	 * @return boolean
	 */
	public function hasClass($class = null)
	{
		if ($class !== null) {
			$classes = explode(' ', $this->getClass());
			return in_array($class, $classes);
		} else {
			return $this->hasAttribute('class');
		}
	}	
}

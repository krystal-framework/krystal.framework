<?php

/**
 * This file is part of the Krystal Framework
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
     * @return \Krystal\Form\NodeElement
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
     * Append children elements
     * 
     * @param array $nodes
     * @return \Krystal\Form\NodeElement
     */
    public function appendChildren(array $nodes);

    /**
     * Appends child element prepending or appending a text
     * 
     * @param \Krystal\Form\NodeElement $nodeElement
     * @param string $text
     * @param boolean $append Whether to append or prepend
     * @return \Krystal\Form\NodeElement
     */
    public function appendChildWithText(NodeElement $nodeElement, $text, $append = true);

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
     * @throws \LogicException When trying to append existing property
     * @return \Krystal\Form\NodeElement
     */
    public function addProperty($property);

    /**
     * Adds a property on demand
     * 
     * @param string $property
     * @param mixed $value
     * @return \Krystal\Form\NodeElement
     */
    public function addPropertyOnDemand($property, $value);

    /**
     * Adds many properties at once
     * 
     * @param array $properties
     * @return \Krystal\Form\NodeElement
     */
    public function addProperties(array $properties);

    /**
     * Checks whether property has been added
     * 
     * @param string $property
     * @return boolean
     */
    public function hasProperty($property);

    /**
     * Returns all defined properties
     * 
     * @return array
     */
    public function getProperties();

    /**
     * Checks whether an attribute is a property
     * 
     * @param string $attribute
     * @return boolean
     */
    public function isProperty($attribute);

    /**
     * Adds an attribute
     * 
     * @param string $attribute
     * @param string $value
     * @throws \LogicException If trying to set existing attribute
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

    /**
     * Returns all defined attributes
     * 
     * @return array
     */
    public function getAttributes();

    /**
     * Checks whether attribute is defined
     * 
     * @param string $attribute
     * @return boolean
     */
    public function hasAttribute($attribute);

    /**
     * Returns attribute value
     * 
     * @param string $attribute
     * @param mixed $default Default value to be returned in case attribute doesn't exist
     * @return string
     */
    public function getAttribute($attribute, $default = false);

    /**
     * Adds data-* attribute
     * 
     * @param string $data
     * @param string $value
     * @return \Krystal\Form\NodeElement
     */
    public function addData($data, $value);

    /**
     * Checks whether data-* attribute has been set
     * 
     * @param string $data
     * @return boolean
     */
    public function hasData($data);

    /**
     * Returns data-* attribute
     * 
     * @param string $data
     * @return string
     */
    public function getData($data);

    /**
     * Adds "required" property on demand
     * 
     * @param boolean $value
     * @return \Krystal\Form\NodeElement
     */
    public function setRequired($value);

    /**
     * Checks whether "required" property has been set
     * 
     * @return boolean
     */
    public function isRequired();

    /**
     * Adds "checked" property on demand
     * 
     * @param boolean $value
     * @return \Krystal\Form\NodeElement
     */
    public function setChecked($value);

    /**
     * Checks whether "checked" property has been set
     * 
     * @return boolean
     */
    public function isChecked();

    /**
     * Adds "selected" property on demand
     * 
     * @param boolean $value
     * @return \Krystal\Form\NodeElement
     */
    public function setSelected($value);

    /**
     * Checks whether "selected" property has been set
     * 
     * @return boolean
     */
    public function isSelected();

    /**
     * Adds "disabled" property on demand
     * 
     * @param boolean $value
     * @return \Krystal\Form\NodeElement
     */
    public function setDisabled($value);

    /**
     * Checks whether "selected" property has been set
     * 
     * @return boolean
     */
    public function isDisabled();

    /**
     * Adds "min" attribute
     * 
     * @param string $value
     * @return \Krystal\Form\NodeElement
     */
    public function setMin($value);

    /**
     * Returns "min" attribute value if present
     * 
     * @return mixed
     */
    public function getMin();

    /**
     * Checks whether "min" attribute has beet set
     * 
     * @return boolean
     */
    public function hasMin();

    /**
     * Adds "max" attribute
     * 
     * @param string $value
     * @return \Krystal\Form\NodeElement
     */
    public function setMax($value);

    /**
     * Returns "max" attribute value if present
     * 
     * @return mixed
     */
    public function getMax();

    /**
     * Checks whether "max" attribute has beet set
     * 
     * @return boolean
     */
    public function hasMax();

    /**
     * Adds "placeholder" attribute
     * 
     * @param string $value
     * @return \Krystal\Form\NodeElement
     */
    public function setPlaceholder($value);

    /**
     * Returns "placeholder" attribute value if present
     * 
     * @return mixed
     */
    public function getPlaceholder();

    /**
     * Checks whether "placeholder" attribute has beet set
     * 
     * @return boolean
     */
    public function hasPlaceholder();

    /**
     * Adds "value" attribute
     * 
     * @param string $value
     * @return \Krystal\Form\NodeElement
     */
    public function setValue($value);

    /**
     * Returns "value" attribute value if present
     * 
     * @return mixed
     */
    public function getValue();

    /**
     * Checks whether "value" attribute has beet set
     * 
     * @return boolean
     */
    public function hasValue();

    /**
     * Adds "name" attribute
     * 
     * @param string $value
     * @return \Krystal\Form\NodeElement
     */
    public function setName($value);

    /**
     * Returns "name" attribute value if present
     * 
     * @return mixed
     */
    public function getName();

    /**
     * Checks whether "name" attribute has beet set
     * 
     * @return boolean
     */
    public function hasName();

    /**
     * Adds "id" attribute
     * 
     * @param string $value
     * @return \Krystal\Form\NodeElement
     */
    public function setId($value);

    /**
     * Returns "id" attribute value if present
     * 
     * @return mixed
     */
    public function getId();

    /**
     * Checks whether "id" attribute has beet set
     * 
     * @return boolean
     */
    public function hasId();

    /**
     * Adds "class" attribute
     * 
     * @param string $class
     * @return \Krystal\Form\NodeElement
     */
    public function setClass($class);

    /**
     * Returns "class" attribute
     * 
     * @return \Krystal\Form\NodeElement
     */
    public function getClass();

    /**
     * Appends a class
     * 
     * @param string $class
     * @return \Krystal\Form\NodeElement
     */
    public function addClass($class);

    /**
     * Determines whether element has a class
     * 
     * @param string $class
     * @return boolean
     */
    public function hasClass($class = null);

    /**
     * Adds "href" attribute
     * 
     * @param string $value
     * @return \Krystal\Form\NodeElement
     */
    public function setHref($value);

    /**
     * Returns the value of "href" attribute if present
     * 
     * @return string
     */
    public function getHref();

    /**
     * Checks whether "href" attribute has been set
     * 
     * @return boolean
     */
    public function hasHref();

    /**
     * Adds "title" attribute
     * 
     * @param string $value
     * @return \Krystal\Form\NodeElement
     */
    public function setTitle($value);

    /**
     * Returns the value of "title" attribute if present
     * 
     * @return string
     */
    public function getTitle();

    /**
     * Checks whether "title" attribute has been set
     * 
     * @return boolean
     */
    public function hasTitle();

    /**
     * Adds "type" attribute
     * 
     * @param string $value
     * @return \Krystal\Form\NodeElement
     */
    public function setType($value);

    /**
     * Returns the value of "type" attribute if present
     * 
     * @return string
     */
    public function getType();

    /**
     * Checks whether "type" attribute has been set
     * 
     * @return boolean
     */
    public function hasType();

    /**
     * Adds "target" attribute
     * 
     * @param string $value
     * @return \Krystal\Form\NodeElement
     */
    public function setTarget($value);

    /**
     * Returns the value of "target" attribute if present
     * 
     * @return string
     */
    public function getTarget();

    /**
     * Checks whether "target" attribute has been set
     * 
     * @return boolean
     */
    public function hasTarget();

    /**
     * Adds "alt" attribute
     * 
     * @param string $value
     * @return \Krystal\Form\NodeElement
     */
    public function setAlt($value);

    /**
     * Returns the value of "alt" attribute if present
     * 
     * @return string
     */
    public function getAlt();

    /**
     * Checks whether "alt" attribute has been set
     * 
     * @return boolean
     */
    public function hasAlt();
}

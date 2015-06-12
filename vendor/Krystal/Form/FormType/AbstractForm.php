<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\FormType;

use Krystal\Form\Element;
use Krystal\Form\Input;

abstract class AbstractForm
{
	/**
	 * Stack of registered elements
	 * 
	 * @var array
	 */
	protected $stack = array();

	/**
	 * Raw input data
	 * 
	 * @var array
	 */
	protected $input = array();

	/**
	 * Form name
	 * 
	 * @var string
	 */
	protected $name;

	/**
	 * Addition form data
	 * 
	 * @var array
	 */
	protected $data = array();

	/**
	 * Whether form is registered or not
	 * 
	 * @var boolean
	 */
	protected $registered = false;

	/**
	 * State initialization
	 * 
	 * @param array $input
	 * @return void
	 */
	public function __construct(array $input = array())
	{
		// Get form name
		$name = method_exists($this, 'getName') ? $this->getName() : null;
		$this->input = new Input($input, $name);
	}

	/**
	 * Determines whether current record is a new one
	 * Is based on PK's key in elements definition provided by user
	 * 
	 * @return boolean
	 */
	final public function isNewRecord()
	{
		$elements = $this->getElements();

		// Array key's id
		$id = 'pk';

		foreach ($elements as $name => $options) {
			// Attempt to find a registered primary key
			if (isset($options[$id]) && $options[$id] == true) {
				// OK, found it, time to get an input with found PK's name
				$pk = $this->input->get($name);

				// If it's positive like
				if ($pk) {
					// Then it's not a new record
					return false;
				} else {
					// Otherwise it is
					return true;
				}
			}
		}

		// By default, we consider as false
		return false;
	}

	/**
	 * Returns element's value
	 * 
	 * @param string $element
	 * @return mixed
	 */
	final public function getInput($element)
	{
		return $this->input->get($element);
	}

	/**
	 * Determines whether a form has data
	 * 
	 * @param string $key
	 * @return boolean
	 */
	final public function hasData($key)
	{
		return isset($this->data[$key]);
	}

	/**
	 * Sets form data
	 * 
	 * @param string $key The name
	 * @param mixed $value
	 * @return void
	 */
	final public function setData($key, $value)
	{
		$this->data[$key] = $value;
	}

	/**
	 * Returns form data
	 * 
	 * @param string $key
	 * @param boolean $default
	 * @return mixed
	 */
	final public function getData($key, $default = false)
	{
		if ($this->hasData($key)) {
			return $this->data[$key];
		} else {
			return $default;
		}
	}

	/**
	 * Registers all defined elements to the stack
	 * 
	 * @return void
	 */
	protected function register()
	{
		foreach ($this->getElements() as $name => $options) {
			if (isset($options['element'])) {

				$options = $this->getDefaultAttributes($options);

				// @TODO That needs to be abstracted, definitely
				switch ($options['element']['type']) {
					
					case 'radio':
						$this->stack[$name] = Element\Radio::factory($this->input, $name, $options);
					break;
					
					case 'date':
						$this->stack[$name] = Element\Date::factory($this->input, $name, $options);
					break;
					
					case 'file':
						$this->stack[$name] = Element\File::factory($this->input, $name, $options);
					break;
					
					case 'color':
						$this->stack[$name] = Element\Color::factory($this->input, $name, $options);
					break;
					
					case 'email':
						$this->stack[$name] = Element\Email::factory($this->input, $name, $options);
					break;
					
					case 'image':
						$this->stack[$name] = Element\Image::factory($this->input, $name, $options);
					break;
					
					case 'range':
						$this->stack[$name] = Element\Range::factory($this->input, $name, $options);
					break;
					
					case 'number':
						$this->stack[$name] = Element\Number::factory($this->input, $name, $options);
					break;
					
					case 'url':
						$this->stack[$name] = Element\Url::factory($this->input, $name, $options);
					break;

					case 'text':
						$this->stack[$name] = Element\Text::factory($this->input, $name, $options);
					break;

					case 'select':
						$this->stack[$name] = Element\Select::factory($this->input, $name, $options);
					break;

					case 'checkbox':
						$this->stack[$name] = Element\Checkbox::factory($this->input, $name, $options);
					break;

					case 'hidden':
						$this->stack[$name] = Element\Hidden::factory($this->input, $name, $options);
					break;

					case 'textarea':
						$this->stack[$name] = Element\Textarea::factory($this->input, $name, $options);
					break;
				}
			}
		}

		$this->registered = true;
	}

	/**
	 * Calls a method before registering
	 * 
	 * @param array $attrs
	 * @return array
	 */
	protected function getDefaultAttributes(array $attrs)
	{
		return $attrs;
	}

	/**
	 * Prints an element by its associated name
	 * 
	 * @param string $name
	 * @return void
	 */
	public function show($name)
	{
		echo $this->render($name);
	}

	/**
	 * Returns error messages
	 * 
	 * @return array
	 */
	public function getErrors()
	{
	}

	/**
	 * Checks whether form is valid
	 * 
	 * @return boolean
	 */
	public function isValid()
	{
	}

	/**
	 * Returns a value of an element
	 * 
	 * @return string
	 */
	public function get()
	{
	}

	/**
	 * Returns form elements
	 * 
	 * @return array
	 */
	abstract public function getElements();
}

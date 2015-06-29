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

use Krystal\Form\Input;
use Krystal\I18n\TranslatorInterface;

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
	 * Form attribute names that must be translated
	 * 
	 * @var array
	 */
	protected $translatable = array('placeholder', 'title');

	/**
	 * Attribute translator
	 * 
	 * @var \Krystal\I18n\TranslatorInterface
	 */
	protected $translator;

	/**
	 * State initialization
	 * 
	 * @param array $input
	 * @param \Krystal\I18n\TranslatorInterface $translator
	 * @return void
	 */
	public function __construct(array $input = array(), TranslatorInterface $translator = null)
	{
		// Get form name
		$name = method_exists($this, 'getName') ? $this->getName() : null;
		$this->input = new Input($input, $name);
		$this->translator = $translator;
	}

	/**
	 * Adds an attribute that must be translatable
	 * 
	 * @param string $attribute
	 * @return void
	 */
	public function addTranslateableAttr($attribute)
	{
		array_push($this->translatable, $attribute);
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
	 * Attempts to build an instance
	 * 
	 * @param string $name Element's name
	 * @param array $options Attached options
	 * @return string
	 */
	final protected function renderElement($name, array $options)
	{
		$options = $this->getDefaultAttributes($options);

		// Since classes follow PSR-2, the first letter must be always upper cased
		// And it would be better to avoid cases, to avoid conflicts
		$type = ucfirst(strtolower($options['element']['type']));

		// Shared namespace for all elements
		$namespace = '\Krystal\Form\Element\\';

		// The name of a factory method each element must implement
		$method = 'factory';

		// Build compliant class name
		$class = $namespace.$type;

		if (class_exists($class)) {

			// Build invokable method name
			$invokable = $namespace . $type . '::' . $method;

			// Translate attributes on demand
			$options = $this->translateAttributes($options);

			// Invoke a factory returning element
			return call_user_func($invokable, $this->input, $name, $options);

		} else {

			return null;
		}
	}

	/**
	 * Translates form attributes
	 * 
	 * @param array $attributes
	 * @return array
	 */
	protected function translateAttributes(array $options)
	{
		if (isset($options['element']['attributes']) && is_array($options['element']['attributes']) && is_object($this->translator)) {

			foreach ($options['element']['attributes'] as $attribute => $value) {
				// Do translate in case the attribute belongs to a map we defined
				if (in_array($attribute, $this->translatable)) {
					$options['element']['attributes'][$attribute] = $this->translator->translate($value);
				}
			}
		}

		// Always return the array regarding success or failure
		return $options;
	}

	/**
	 * Registers all defined elements to the stack
	 * 
	 * @return void
	 */
	protected function register()
	{
		foreach ($this->getElements() as $name => $options) {
			if (isset($options['element']['type'])) {

				$element = $this->renderElement($name, $options);

				// The call returns null if can not register
				if ($element !== null) {
					// Do register now, since its safe
					$this->stack[$name] = $element;
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

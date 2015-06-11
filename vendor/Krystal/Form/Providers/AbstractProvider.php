<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Providers;

use Krystal\Form\Selectbox\OptionBox;
use LogicException;

//@TODO: Get rid of session's global state
abstract class AbstractProvider
{
	/**
	 * Default value in collection to choice from
	 * 
	 * @var string
	 */
	protected $default;

	/**
	 * Unique namespace in storage for this provider
	 * 
	 * @var string
	 */
	protected $ns;

	/**
	 * Current data
	 * 
	 * @var array
	 */
	protected $values = array();

	/**
	 * State initialization
	 * 
	 * @param string $ns
	 * @param string $default
	 * @param array $data
	 * @return void
	 */
	public function __construct($ns, $default, array $values)
	{
		$this->ns = $ns;
		$this->default = $default;
		$this->values = $values;
	}

	/**
	 * Returns all prepared entities
	 * 
	 * @return array
	 */
	final protected function getAllPrepared()
	{
		$ob = new OptionBox();

		$ob->setOptions($this->values);
		$ob->setCurrent($this->getData());

		return $ob->getAll();
	}

	/**
	 * Defines data's key
	 * 
	 * @return boolean
	 */
	final protected function setData($value)
	{
		//@TODO FIX: $this->has($value)
		if (1) {
			$_SESSION[$this->ns] = $value;
			return true;
			
		} else {
			// Could not set, $sort sort doesn't belong to values
			return false;
		}
	}

	/**
	 * Returns data
	 * 
	 * @return mixed
	 */
	final public function getData()
	{
		if (isset($_SESSION[$this->ns])) {
			return $_SESSION[$this->ns];
		}

		// If can't find in storage, then return default
		return $this->default;
	}

	/**
	 * Checks whether option exists
	 * 
	 * @param string $option Target option key
	 * @return boolean True if option exists, false if not
	 */
	final protected function has($option)
	{
		return in_array($option, array_keys($this->values));
	}
}

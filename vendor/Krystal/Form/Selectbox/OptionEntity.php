<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Selectbox;

class OptionEntity
{
	/**
	 * Current option's value
	 * 
	 * @var string
	 */
	private $value;

	/**
	 * Current option's name
	 * 
	 * @var string
	 */
	private $name;

	/**
	 * Whether current option is current or not
	 * 
	 * @var boolean
	 */
	private $current;

	/**
	 * Defines current value
	 * 
	 * @param string $value
	 * @return \Krystal\Form\Selectbox\OptionEntity
	 */
	public function setValue($value)
	{
		$this->value = $value;
		return $this;
	}

	/**
	 * Returns current option value
	 * 
	 * @return string
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * Sets current option name
	 * 
	 * @param string $name
	 * @return \Krystal\Form\Selectbox\OptionEntity
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * Returns defined name
	 * 
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Sets whether this option is current or not
	 * 
	 * @param boolean $current
	 * @return \Krystal\Form\Selectbox\OptionEntity
	 */
	public function setCurrent($current)
	{
		$this->current = $current;
		return $this;
	}

	/**
	 * Returns true if this option is considered as a current one
	 * 
	 * @return boolean
	 */
	public function getCurrent()
	{
		return $this->current;
	}

	/**
	 * Returns true if this option is considered as a current one
	 * 
	 * @return boolean
	 */
	public function isCurrent()
	{
		return $this->getCurrent();
	}
}

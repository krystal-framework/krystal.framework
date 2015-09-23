<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Providers;

use Krystal\Form\Selectbox\OptionBox;
use Krystal\Http\PersistentStorageInterface;
use LogicException;

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
	 * Persistent storage service
	 * 
	 * @var \Krystal\Http\PersistentStorageInterface
	 */
	protected $storage;

	/**
	 * State initialization
	 * 
	 * @param \Krystal\Http\PersistentStorageInterface $storage
	 * @param string $ns Namespace to be taken in a storage
	 * @param string $default Default value to return in case requested isn't persisted
	 * @param array $data
	 * @return void
	 */
	public function __construct(PersistentStorageInterface $storage, $ns, $default, array $values)
	{
		$this->storage = $storage;
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
	 * @param string $value A value to be written in provided namespace
	 * @return void
	 */
	final protected function setData($value)
	{
		$this->storage->set($this->ns, $value);
	}

	/**
	 * Returns data from a storage if present
	 * If not returns default value
	 * 
	 * @return mixed
	 */
	final public function getData()
	{
		if ($this->storage->has($this->ns)) {
			return $this->storage->get($this->ns);
		} else {
			// If can't find in storage, then return default
			return $this->default;
		}
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

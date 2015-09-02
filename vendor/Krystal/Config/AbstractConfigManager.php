<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Config;

use Krystal\Stdlib\VirtualEntity;

abstract class AbstractConfigManager
{
	/**
	 * Any compliant configuration adapter
	 * 
	 * @var \Config\Adapter\AdapterInterface
	 */
	protected $adapter;

	/**
	 * Configuration entity object
	 * 
	 * @var \Krystal\Stdlib\VirtualEntity
	 */
	protected $entity;

	/**
	 * State initialization
	 * 
	 * @param \Krystal\Config\Adapter\ConfigAdapterInterface $adapter
	 * @return void
	 */
	public function __construct(/*ConfigAdapterInterface*/ $adapter)
	{
		$this->adapter = $adapter;
	}

	/**
	 * Populates the entity
	 * 
	 * @return void
	 */
	abstract protected function populate();

	/**
	 * Returns a value
	 * 
	 * @param string $key
	 * @param mixed $default Default value to be returned if $key is absent
	 * @return mixed
	 */
	final public function get($key, $default = false)
	{
		return $this->adapter->get($key, $default);
	}

	/**
	 * Writes value
	 * 
	 * @param array $input
	 * @return boolean
	 */
	final public function write(array $input)
	{
		$this->adapter->setPair($input);
		$this->adapter->save();

		return true;
	}

	/**
	 * Checks whether configuration key exist
	 * 
	 * @param string $key
	 * @return boolean
	 */
	final public function exists($key)
	{
		return $this->adapter->exists($key);
	}

	/**
	 * Returns the entity
	 * 
	 * @return \Krystal\Stdlib\VirtualEntity
	 */
	final public function getEntity()
	{
		// Lazy initialization
		if (is_null($this->entity)) {
			$this->entity = new VirtualEntity();
			$this->populate();
		}

		return $this->entity;
	}
}

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

use Krystal\Stdlib\VirtualEntity as ConfigEntity;
use Krystal\Config\Adapter\ConfigAdapterInterface;

abstract class AbstractConfigManager
{
	/**
	 * Any compliant config adapter
	 * 
	 * @var \Config\Adapter\AdapterInterface
	 */
	protected $adapter;

	/**
	 * Configuration entity object
	 * 
	 * @var
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

	public function exists($key)
	{
		return $this->adapter->exists($key);
	}
	
	/**
	 * Returns the entity
	 * 
	 * @return 
	 */
	public function getEntity()
	{
		if (is_null($this->entity)) {
			$this->entity = new ConfigEntity();
			$this->populate();
		}
		
		return $this->entity;
	}
}

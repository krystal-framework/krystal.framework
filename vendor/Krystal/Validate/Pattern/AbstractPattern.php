<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Pattern;

abstract class AbstractPattern
{
	/**
	 * Target overrides
	 * 
	 * @var array
	 */
	protected $overrides = array();

	/**
	 * State initialization
	 * 
	 * @param array $overrides
	 * @return void
	 */
	public function __construct(array $overrides = array())
	{
		$this->overrides = $overrides;
		$this->init();
	}

	/**
	 * Initialization logic
	 */
	protected function init()
	{
	}
	
	/**
	 * Prepares a definition
	 * 
	 * @param array $definition
	 * @return array
	 */
	final protected function getWithDefaults(array $definition)
	{
		return array_replace_recursive($definition, $this->overrides);
	}

	/**
	 * @return void
	 */
	final protected function override($overrides)
	{
		$this->overrides = $this->getWithDefaults($overrides);
	}

	/**
	 * Return definitions
	 * 
	 * @return array
	 */
	abstract public function getDefinition();
}
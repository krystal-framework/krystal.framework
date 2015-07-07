<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Filter;

final class QueryContainer implements QueryContainerInterface
{
	/**
	 * Query data
	 * 
	 * @var array
	 */
	private $data = array();

	/**
	 * State initialization
	 * 
	 * @param mixed $data
	 * @return void
	 */
	public function __construct($data = array())
	{
		$this->data = $data;
	}

	/**
	 * Returns key's value if exists
	 * 
	 * @param string $key
	 * @return string
	 */
	public function get($key)
	{
		$default = false;

		if (!is_array($this->data)) {
			return $default;
		}

		if (isset($this->data[$key])) {
			return $this->data[$key];
		} else {
			return $default;
		}
	}
}

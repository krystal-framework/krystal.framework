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
	 * A group name for all elements
	 * 
	 * @var string
	 */
	private $group;

	/**
	 * State initialization
	 * 
	 * @param array $request All GET-request data
	 * @param string $group Group name
	 * @return void
	 */
	public function __construct(array $request, $group)
	{
		if (isset($request[$group]) && is_array($request[$group])) {
			$this->data = $request[$group];
		}

		$this->group = $group;
	}

	/**
	 * Returns grouped element name
	 * 
	 * @param string $name
	 * @return string
	 */
	public function getElementName($name)
	{
		return sprintf('%s[%s]', $this->group, $name);
	}

	/**
	 * Checks whether a filter has been applied
	 * 
	 * @return boolean
	 */
	public function isApplied()
	{
		return !empty($this->data);
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

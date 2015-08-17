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

final class QueryGenerator
{
	/**
	 * Base route
	 * 
	 * @var string
	 */
	private $route;

	/**
	 * State initialization
	 * 
	 * @param string $route Base route
	 * @return void
	 */
	public function __construct($route)
	{
		$this->route = $route;
	}

	/**
	 * Generates URL
	 * 
	 * @param array $data
	 * @return string
	 */
	public function generate(array $data)
	{
		$url = '?';

		$url .= http_build_query($data);
		$url = str_replace('%25s', '%s', $url);

		return $this->route.$url;
	}
}

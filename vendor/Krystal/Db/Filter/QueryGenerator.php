<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Filter;

final class QueryGenerator implements QueryGeneratorInterface
{
	/**
	 * Base route
	 * 
	 * @var string
	 */
	private $route;

	/**
	 * Target placeholder
	 * 
	 * @var string
	 */
	private $placeholder;

	/**
	 * State initialization
	 * 
	 * @param string $route Base route
	 * @param string $placeholder Query string page placeholder
	 * @return void
	 */
	public function __construct($route, $placeholder)
	{
		$this->route = $route;
		$this->placeholder = $placeholder;
	}

	/**
	 * Generates URL
	 * 
	 * @param array $data
	 * @return string
	 */
	public function generate(array $data)
	{
		// Query start
		$url = '?';

		$url .= http_build_query($data);
		$url = str_replace(rawurlencode($this->placeholder), $this->placeholder, $url);

		return $this->route.$url;
	}
}

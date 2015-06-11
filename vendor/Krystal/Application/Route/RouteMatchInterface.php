<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Route;

interface RouteMatchInterface
{
	/**
	 * Defines matched URI
	 * 
	 * @param string $matchedURI
	 * @return \Krystal\Application\Route\RouteMatch
	 */
	public function setMatchedURI($matchedURI);

	/**
	 * Returns matched URI
	 * 
	 * @return string
	 */
	public function getMatchedURI();

	/**
	 * Defines a matched URI template
	 * 
	 * @param string $matchedURITemplate
	 * @return \Krystal\Application\Route\RouteMatch
	 */
	public function setMatchedURITemplate($matchedURITemplate);

	/**
	 * Returns matched URI template
	 * 
	 * @return string
	 */
	public function getMatchedURITemplate();

	/**
	 * Defines a method
	 * 
	 * @param string $method
	 * @return \Krystal\Application\Route\RouteMatch
	 */
	public function setMethod($method);

	/**
	 * Returns route method
	 * 
	 * @return string
	 */
	public function getMethod();

	/**
	 * Defines route variables
	 * 
	 * @param array $variables
	 * @return \Krystal\Application\Route\RouteMatch
	 */
	public function setVariables(array $variables);

	/**
	 * Returns route variables
	 * 
	 * @return array
	 */
	public function getVariables();
}

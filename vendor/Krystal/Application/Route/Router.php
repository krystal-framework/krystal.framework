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

use Krystal\Application\Route\RouteMatch;
use Krystal\Application\Route\RouterInterface;

/**
 * Here we are dealing with several concepts:
 * 
 * 1. URI Template
 * 2. Actual URI
 * 3. Extracting parameters. This is a difference between matched URI template and actual URI string
 */
final class Router implements RouterInterface
{
	/**
	 * Default reg-ex patterns with its place-holders
	 * 
	 * @var array
	 */
	private $replacements = array(
		'(:var)' =>	'([^/]*)'
	);

	/**
	 * Matches a URI string against a route map
	 * 
	 * @param string $segment The actual segment to match against
	 * @param array $map Target route map to compare against
	 * @return boolean|\Krystal\Application\Route\RouteMatch
	 */
	public function match($segment, array $map)
	{
		foreach ($map as $index => $uriTemplate) {
			$matches = array();
			if (preg_match($this->buildRegEx($uriTemplate), $segment, $matches) === 1) {
				$matchedURI = array_shift($matches);

				$routeMatch = new RouteMatch();
				$routeMatch->setMatchedUri($matchedURI)
							->setMatchedUriTemplate($uriTemplate)
							->setVariables($matches);
				
				return $routeMatch;
			}
		}

		// By default, we have no matches
		return false;
	}

	/**
	 * Return place-holders only
	 * 
	 * @return array
	 */
	private function getPlaceholders()
	{
		return array_keys($this->replacements);
	}

	/**
	 * Returns regular expressions
	 * 
	 * @return array
	 */
	private function getPatterns()
	{
		return array_values($this->replacements);
	}

	/**
	 * Builds a regular expression according to URI template
	 * 
	 * @param string $uriTemplate string
	 * @return string Prepared regular expression
	 */
	private function buildRegex($uriTemplate)
	{
		$pattern = str_replace($this->getPlaceholders(), $this->getPatterns(), $uriTemplate);
		$regex = '~^' . $pattern . '$~i';

		return $regex;
	}
}

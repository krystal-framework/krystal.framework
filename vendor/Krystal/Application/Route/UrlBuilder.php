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

use RuntimeException;

final class UrlBuilder implements UrlBuilderInterface
{
	/**
	 * Map manager
	 * 
	 * @var \Krystal\Application\Route\MapManager
	 */
	private $mapManager;

	/**
	 * Variable parameter in routes to be substituted
	 * 
	 * @const string
	 */
	const ROUTE_PARAM_VAR = '(:var)';

	/**
	 * State initialization
	 * 
	 * @param \Krystal\Application\Route\MapManager $mapManager
	 * @return void
	 */
	public function __construct(MapManager $mapManager)
	{
		$this->mapManager = $mapManager;
	}

	/**
	 * Count amount of variables in given URI template
	 * 
	 * @param string $template URI template
	 * @return integer
	 */
	private function getVarCount($template)
	{
		return substr_count($template, self::ROUTE_PARAM_VAR);
	}

	/**
	 * Checks whether URI template has at least one variable
	 * 
	 * @param string $template URI template
	 * @return boolean
	 */
	private function hasVar($template)
	{
		return strpos($template, self::ROUTE_PARAM_VAR) !== false;
	}

	/**
	 * Prepares URL
	 * 
	 * @param string $template URI template
	 * @param array $vars
	 * @throws RuntimeException When count mismatches
	 * @return string
	 */
	private function prepare($template, array $vars)
	{
		$varCount = $this->getVarCount($template);
		$currentCount = count($vars);

		// Ensure the amount of variables is the same as in the target array
		if ($varCount !== $currentCount) {
			throw new RuntimeException(sprintf(
				'Controller "%s" expects %s variables, %s passed', $controller, $varCount, $currentCount
			));
		}

		$template = str_replace(self::ROUTE_PARAM_VAR, '%s', $template);
		return vsprintf($template, $vars);
	}

	/**
	 * Builds URL
	 * 
	 * @param string $controller Controller name in format <Module>:<Controller>@<Action>
	 * @param array $vars
	 * @return string Null on failure
	 */
	public function build($controller, array $vars = array())
	{
		$template = $this->mapManager->getUrlTemplateByController($controller);

		if ($template !== false) {
			if ($this->hasVar($template)) {
				return $this->prepare($template, $vars);
				
			} else {
				return $template;
			}

		} else {

			// Wrong controller, so nothing to return there
			return null;
		}
	}
}

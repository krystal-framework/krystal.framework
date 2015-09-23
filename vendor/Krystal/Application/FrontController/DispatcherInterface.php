<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\FrontController;

use Krystal\Application\FrontController\ControllerFactory;

interface DispatcherInterface
{
	/**
	 * Renders as a string
	 * The last thing we could possibly do
	 * 
	 * @param string $matchedURITemplate
	 * @param array $params
	 * @return string
	 */
	public function render($matchedURITemplate, array $params);
}

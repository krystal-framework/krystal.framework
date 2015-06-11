<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\FrontController;

interface ControllerFactoryInterface
{
	/**
	 * Builds a controller instance
	 * 
	 * @param string $controller PSR-0 Compliant path
	 * @param array $options Route options
	 * @return \Krystal\Application\Controller\AbstractController
	 */
	public function build($controller, array $options);
}

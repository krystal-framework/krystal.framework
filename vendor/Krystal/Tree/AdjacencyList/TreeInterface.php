<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Tree\AdjacencyList;

use Krystal\Tree\AdjacencyList\Render\AbstractRenderer;

interface TreeInterface
{
	/**
	 * Renders an interface
	 * 
	 * @param AbstractRenderer $renderer Any renderer which extends AbstractRenderer
	 * @param string $active
	 * @return string
	 */
	public function render(AbstractRenderer $renderer, $active = null);
}

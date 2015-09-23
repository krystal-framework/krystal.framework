<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Tree\AdjacencyList;

use Closure;

interface BreadcrumbBuilderInterface
{
	/**
	 * Makes breadcrumbs
	 * 
	 * @param \Closure $visitor
	 * @return array
	 */
	public function makeAll(Closure $visitor);
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Paginate\Style;

interface StyleInterface
{
	/**
	 * Filter page numbers via current style adapter
	 * 
	 * @param array $pages
	 * @param integer $page
	 * @return array
	 */
	public function getPageNumbers(array $pages, $page);
}

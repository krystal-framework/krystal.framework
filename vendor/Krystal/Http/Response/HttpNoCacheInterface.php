<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\Response;

interface HttpNoCacheInterface
{
	/**
	 * Appends required headers to disable HTTP cache
	 * 
	 * @return void
	 */
	public function configure();
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form;

interface InputInterface
{
	/**
	 * Checks whether input has a key
	 * 
	 * @param string $key
	 * @return boolean
	 */
	public function has($key);
}

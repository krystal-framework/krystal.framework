<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Text;

interface SlugGeneratorInterface
{
	/**
	 * Generates a slug
	 * 
	 * @param string $string Target string
	 * @return string
	 */
	public function generate($string);
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Captcha\Standard\Image;

interface ImageGeneratorInterface
{
	/**
	 * Renders the CAPTCHA
	 * 
	 * @param string $text Text to be rendered
	 * @return void
	 */
	public function render($text);
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Compressor;

interface CompressorInterface
{
	/**
	 * Compresses the string
	 * 
	 * @param string $content
	 * @return string 
	 */
	public function compress($content);
}
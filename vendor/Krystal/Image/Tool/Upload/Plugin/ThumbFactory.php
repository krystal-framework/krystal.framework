<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Image\Tool\Upload\Plugin;

final class ThumbFactory
{
	/**
	 * Builds thumb uploader
	 * 
	 * @param string $dir Target directory
	 * @param integer $quality Desired quality for thumbs
	 * @param array $options
	 * @return \Krystal\Image\Tool\Upload\Plugin\ThumbFactory
	 */
	public function build($dir, $quality, array $options = array())
	{
		// Alter default quality on demand
		if (isset($options['quality'])) {
			$quality = $options['quality'];
		}
		
		return new Thumb($dir, $options['dimensions'], $quality);
	}
}

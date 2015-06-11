<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Image\Tool\Upload;

use Krystal\Image\Tool\Upload\Plugin\OriginalSizeFactory;
use Krystal\Image\Tool\Upload\Plugin\ThumbFactory;
use Krystal\Http\FileTransfer\UploadChain;
use InvalidArgumentException;

abstract class UploaderFactory
{
	/**
	 * Builds image uploader chain
	 * 
	 * @param string $dir
	 * @param array $plugins
	 * @throws \InvalidArgumentException when failing to load any of plugins
	 * @return \Krystal\Http\FileTransfer\UploadChain
	 */
	public static function build($dir, array $plugins)
	{
		if (count($plugins) == 0) {
			throw new InvalidArgumentException('There must be at least one provided plugin for image uploader');
		}
		
		// Default image's quality
		$quality = 75;
		$collection = array();
		
		foreach ($plugins as $plugin => $options) {
			switch ($plugin) {
				case 'thumb':
					$thumb = new ThumbFactory();
					$collection[] = $thumb->build($dir, $quality, $options);
					
				break;
				
				case 'original':
					$original = new OriginalSizeFactory();
					$collection[] = $original->build($dir, $quality, $options);
					
				break;
			}
		}
		
		return new UploadChain($collection);
	}
}

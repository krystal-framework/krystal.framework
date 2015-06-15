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

use Krystal\Http\FileTransfer\FileInfo;
use Krystal\Http\FileTransfer\UploaderAwareInterface;
use Krystal\Image\Processor\GD\ImageProcessor;

final class Thumb implements UploaderAwareInterface
{
	/**
	 * Dimenstion collection
	 * 
	 * @var array
	 */
	private $dimensions = array();

	/**
	 * Target directory we're working with
	 * 
	 * @var string
	 */
	private $dir;

	/**
	 * Image quality
	 * 
	 * @var integer|float
	 */
	private $quality;

	/**
	 * State initialization
	 * 
	 * @param string $dir Target directory
	 * @param array $dimensions
	 * @param integer $quality Image's quality
	 * @return void
	 */
	public function __construct($dir, array $dimensions, $quality)
	{
		$this->dir = $dir;
		$this->setDimensions($dimensions);
		$this->quality = $quality;
	}

	/**
	 * Sets or overrides default dimensions
	 * 
	 * @param array $dimensions
	 * @return void
	 */
	public function setDimensions($dimensions)
	{
		$this->dimensions = $dimensions;
	}

	/**
	 * Makes destination folder
	 * 
	 * @param string $id
	 * @param string $width
	 * @param string $height
	 * @return string
	 */
	private function makeDestination($id, $width, $height)
	{
		return sprintf('%s/%s/%sx%s', $this->dir, $id, $width, $height);
	}

	/**
	 * Upload images from the input
	 * 
	 * @param string $id Current id
	 * @param array $files Array of file bags
	 * @return boolean
	 */
	public function upload($id, array $files)
	{
		foreach ($files as $file) {
			if ($file instanceof FileInfo) {
				foreach ($this->dimensions as $index => $dimension) {

					$width = (int) $dimension[0];
					$height = (int) $dimension[1];

					$destination = $this->makeDestination($id, $width, $height);

					// Ensure that destination actually exists
					if (!is_dir($destination)) {
						mkdir($destination, 0777, true);
					}

					$to = sprintf('%s/%s', $destination, $file->getName());

					$imageProcessor = new ImageProcessor($file->getTmpName());
					$imageProcessor->thumb($width, $height);

					// This might fail sometimes
					$imageProcessor->save($to, $this->quality);
				}
			}
		}

		return true;
	}
}

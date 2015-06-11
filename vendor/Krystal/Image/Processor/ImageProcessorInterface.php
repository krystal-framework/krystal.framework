<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Image\Processor;

/* API for Image Processor implementation */
interface ImageProcessorInterface
{
	/**
	 * @const integer
	 */
	const IMG_RIGHT_TOP = 1;

	/**
	 * @const integer
	 */
	const IMG_LEFT_CORNER = 2;

	/**
	 * @const integer
	 */
	const IMG_LEFT_BOTTOM_CORNER = 3;

	/**
	 * @const integer
	 */
	const IMG_RIGHT_BOTTOM_CORNER = 4;

	/**
	 * @const integer
	 */
	const IMG_CENTER_CORNER = 5;

	/**
	 * @const integer
	 */
	const IMG_FLIP_HORIZONTAL = 6;

	/**
	 * @const integer
	 */
	const IMG_FLIP_VERTICAL = 7;

	/**
	 * @const integer
	 */
	const IMG_FLIP_BOTH = 8;

	/**
	 * Flips the image
	 * 
	 * @param integer $type
	 * @throws \UnexpectedValueException if unsupported $type supplied
	 * @return \Krystal\Image\Processor\GD\ImageProcessor
	 */
	public function flip($type);

	/**
	 * Adds a watermark on current image
	 * 
	 * @param string $watermarkFile Path to watermark file
	 * @param integer $corner Corner's position (Which is usually represented via constants)
	 * @param integer $offsetX Offset X
	 * @param integer $offsetY Offset Y
	 * @return \Krystal\Image\Processor\GD\ImageProcessor
	 */
	public function watermark($watermarkFile, $corner = self::IMG_RIGHT_BOTTOM_CORNER, $offsetX = 10, $offsetY = 10);

	/**
	 * Adds grayscale filter to the target image
	 * 
	 * @return \Krystal\Image\Processor\GD\ImageProcessor
	 */
	public function grayscale();

	/**
	 * Makes black and white
	 * 
	 * @return \Krystal\Image\Processor\GD\ImageProcessor
	 */
	public function blackwhite();

	/**
	 * Makes the image negative
	 * 
	 * @return \Krystal\Image\Processor\GD\ImageProcessor
	 */
	public function negative();

	/**
	 * Resizes an image
	 * 
	 * @param integer $x New width
	 * @param integer $y New height
	 * @param boolean $proportional Whether to resize proportionally
	 * @return \Krystal\Image\Processor\GD\ImageProcessor
	 */
	public function resize($x, $y, $proportional = true);

	/**
	 * Crops an image
	 * 
	 * @param integer $width
	 * @param integer $height
	 * @param integer $starX X offset
	 * @param integer $starY Y offset
	 * @return \Krystal\Image\Processor\GD\ImageProcessor
	 */
	public function crop($width, $height, $startX = null, $startY = null);

	/**
	 * Resizes and crops to its best fit the image
	 * 
	 * @param integer $width
	 * @param integer $height
	 * @return \Krystal\Image\Processor\GD\ImageProcessor
	 */
	public function thumb($width, $height);

	/**
	 * Rotates the image
	 * 
	 * @param integer $degrees
	 * @return \Krystal\Image\Processor\GD\ImageProcessor
	 */
	public function rotate($degrees);
}

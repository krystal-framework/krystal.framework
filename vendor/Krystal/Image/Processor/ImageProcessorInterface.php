<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Image\Processor;

/* API for Image Processor implementation */
interface ImageProcessorInterface
{
	const IMG_RIGHT_TOP = 1;
	const IMG_LEFT_CORNER = 2;
	const IMG_LEFT_TOP_CORNER = 3;
	const IMG_LEFT_BOTTOM_CORNER = 4;
	const IMG_RIGHT_BOTTOM_CORNER = 5;
	const IMG_RIGHT_TOP_CORNER = 6;
	const IMG_CENTER_CORNER = 7;
	const IMG_FLIP_HORIZONTAL = 8;
	const IMG_FLIP_VERTICAL = 9;
	const IMG_FLIP_BOTH = 10;

	/**
	 * Adds a text on image
 	 * 
 	 * @param string $text Text to be printed on current image
	 * @param string $fontFile Path to the font file
	 * @param string $size The font size to be used when writing the text
	 * @param array $rbg The optional RGB pallete
	 * @param integer $corner Corner position
	 * @param integer $offsetX
	 * @param integer $offsetY
	 * @param integer $angle
	 * @return \Krystal\Image\Processor\GD\ImageProcessor
 	 */
	public function text($text, $fontFile, $size, array $rgb = array(0, 0, 0), $corner = self::IMG_CENTER_CORNER, $offsetX = 0, $offsetY = 0, $angle = 0);

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

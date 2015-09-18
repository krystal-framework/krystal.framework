<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Image\Processor\GD;

use Krystal\Image\Processor\ImageProcessorInterface;
use UnexpectedValueException;

final class ImageProcessor extends ImageFile implements ImageProcessorInterface
{
	/**
	 * Flips the image
	 * 
	 * @param integer $type
	 * @throws \UnexpectedValueException if unsupported $type supplied
	 * @return \Krystal\Image\Processor\GD\ImageProcessor
	 */
	public function flip($type)
	{
		// Initial positioning
		$x = 0;
		$y = 0;

		// Save original dimensions
		$width = $this->width;
		$height = $this->height;

		switch ($type) {
			case self::IMG_FLIP_BOTH:
				$x = $this->width - 1;
				$y = $this->height - 1;

				$width = -$this->width;
				$height = -$this->height;
			break;

			case self::IMG_FLIP_HORIZONTAL:
				$x = $this->width - 1;
				$width = -$this->width;
			break;

			case self::IMG_FLIP_VERTICAL:
				$y = $this->height - 1;
				$height = -$this->height;
			break;

			default:
				throw new UnexpectedValueException("Invalid flip type's value supplied");
        }

		// Done. Now create a new image
		$image = imagecreatetruecolor($this->width, $this->height);
		$this->preserveTransparency($image);

		imagecopyresampled($image, $this->image, 0, 0, $x, $y, $this->width, $this->height, $width, $height);

		// Override with new one
		$this->setImage($image);

		return $this;
	}

	/**
	 * Adds a watermark on current image
	 * 
	 * @param string $watermarkFile Path to watermark file
	 * @param integer $corner Corner's position (Which is usually represented via constants)
	 * @param integer $offsetX Offset X
	 * @param integer $offsetY Offset Y
	 * @return \Krystal\Image\Processor\GD\ImageProcessor
	 */
	public function watermark($watermarkFile, $corner = self::IMG_RIGHT_BOTTOM_CORNER, $offsetX = 10, $offsetY = 10)
	{
		// The very first thing we gotta do is to load watermark's image file
		$watermark = new ImageFile($watermarkFile);

		// Initial positioning
		$x = 0;
		$y = 0;

		// Walk through supported values
		switch ($corner) {

			case self::IMG_RIGHT_BOTTOM_CORNER:
				$x = $this->width - $watermark->getWidth() - $offsetX;
				$y = $this->height - $watermark->getHeight() - $offsetY;
			break;

			case self::IMG_RIGHT_TOP:
				$x = $this->width - $watermark->getWidth() - $offsetX;
				$y = $offsetY;
			break;

			case self::IMG_LEFT_CORNER:
				$x = $offsetX;
				$y = $offsetY;
			break;

			case self::IMG_LEFT_BOTTOM_CORNER:
				$x = $offsetX;
				$y = $this->height - $watermark->getHeight() - $offsetY;
			break;

			case self::CORNER_CENTER:
				$x = floor(($this->width - $watermark->getWidth()) / 2);
				$y = floor(($this->height - $watermark->getHeight()) / 2);
			break;

			default:
				throw new UnexpectedValueException(sprintf("Unexpected corner's value provided '%s'", $corner));
		}

		// Done with calculations. Now add a watermark on the original loaded image
		imagecopy($this->image, $watermark->getImage(), $x, $y, 0, 0, $watermark->getWidth(), $watermark->getHeight());

		// Free memory now
		unset($watermark);

		return $this;
	}

	/**
	 * Adds grayscale filter to the target image
	 * 
	 * @return \Krystal\Image\Processor\GD\ImageProcessor
	 */
	public function grayscale()
	{
		imagefilter($this->image, \IMG_FILTER_GRAYSCALE);
		return $this;
	}

	/**
	 * Makes black and white
	 * 
	 * @return \Krystal\Image\Processor\GD\ImageProcessor
	 */
	public function blackwhite()
	{
		imagefilter($this->image, \IMG_FILTER_GRAYSCALE);
		imagefilter($this->image, \IMG_FILTER_CONTRAST, -1000);

		return $this;
	}

	/**
	 * Makes the image negative
	 * 
	 * @return \Krystal\Image\Processor\GD\ImageProcessor
	 */
	public function negative()
	{
		imagefilter($this->image, \IMG_FILTER_NEGATE);
		return $this;
	}

	/**
	 * Resizes the image
	 * 
	 * @param integer $x New width
	 * @param integer $y New height
	 * @param boolean $proportional Whether to resize proportionally
	 * @return \Krystal\Image\Processor\GD\ImageProcessor
	 */
	public function resize($x, $y, $proportional = true)
	{
		// Check firstly values
		if ($x === null || $x > $this->width) {
			$x = $this->width;
		}

		if ($y === null || $y > $this->height) {
			$y = $this->height;
		}

		if ($proportional === true) {
			$height = $y;
			$width = round($height / $this->height * $this->width);

			if ($width < $x) {
				$width = $x;
				$height = round($width / $this->width * $this->height);
			}

		} else {
			// When no proportionality required, then use target dimensions
			$width = $x;
			$height = $y;
        }

		// Done calculating. Create a new image in memory
        $image = imagecreatetruecolor($width, $height);

		$this->preserveTransparency($image);

		imagecopyresampled($image, $this->image, 0, 0, 0, 0, $width, $height, $this->width, $this->height);

		$this->setImage($image);

		$this->width = $width;
		$this->height = $height;

		return $this;
	}

	/**
	 * Crops an image
	 * 
	 * @param integer $width
	 * @param integer $height
	 * @param integer $starX X offset
	 * @param integer $starY Y offset
	 * @return \Krystal\Image\Processor\GD\ImageProcessor
	 */
	public function crop($width, $height, $startX = null, $startY = null)
	{
		if ($width === null) {
			$width = $this->width;
		}

		if ($height === null) {
			$height = $this->height;
		}

		if ($startX === null) {
			$startX = floor(($this->width - $width) / 2);
		}

		if ($startY === null) {
			$startY = floor(($this->height - $height) / 2);
		}

		// Calculate dimensions
		$startX = max(0, min($this->width, $startX));
		$startY = max(0, min($this->height, $startY));
		$width = min($width, $this->width - $startX);
		$height = min($height, $this->height - $startY);

		$image = imagecreatetruecolor($width, $height);
		$this->preserveTransparency($image);

		imagecopyresampled($image, $this->image, 0, 0, $startX, $startY, $width, $height, $width, $height);
		$this->setImage($image);
		$this->width = $width;
		$this->height = $height;

		return $this;
	}

	/**
	 * Resizes and crops to its best fit the image
	 * 
	 * @param integer $width
	 * @param integer $height
	 * @return \Krystal\Image\Processor\GD\ImageProcessor
	 */
	public function thumb($width, $height)
	{
		$this->resize($width, $height)
			 ->crop($width, $height);

		return $this;
	}

	/**
	 * Rotates the image
	 * 
	 * @param integer $degrees
	 * @return \Krystal\Image\Processor\GD\ImageProcessor
	 */
	public function rotate($degrees)
	{
		// Cast to the int, in case we got a numeric string
		$degrees = (int) $degrees;

		$this->image = imagerotate($this->image, $degrees, 0);

		$this->width = imagesx($this->image);
		$this->height = imagesy($this->image);

		return $this;
	}

	/**
	 * We need this for GIFs and PNGs
	 * 
	 * @param resource $image
	 * @return void
	 */
	private function preserveTransparency($image)
	{
		$transparencyColor = array(0, 0, 0);

		switch ($this->type) {
			case \IMAGETYPE_GIF:

				$color = imagecolorallocate($image, $transparencyColor[0], $transparencyColor[1], $transparencyColor[2]);

				imagecolortransparent($image, $color);
				imagetruecolortopalette($image, false, 256);

			break;

			case \IMAGETYPE_PNG:

				imagealphablending($image, false);

				$color = imagecolorallocatealpha($image, $transparencyColor[0], $transparencyColor[1], $transparencyColor[2], 0);

				imagefill($image, 0, 0, $color);
				imagesavealpha($image, true);

			break;
		}
	}	
}

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

use RuntimeException;

if (!extension_loaded('gd')) {
	throw new RuntimeException('GD library is not installed');
}

use LogicException;
use Krystal\Image\Processor\ImageFileInterface;

class ImageFile implements ImageFileInterface
{
	/**
	 * Loaded image resource
	 * 
	 * @var resource
	 */
	protected $image;

	/**
	 * Image width
	 * 
	 * @var integer
	 */
	protected $width;

	/**
	 * Image height
	 * 
	 * @var integer
	 */
	protected $height;

	/**
	 * Image type
	 * 
	 * @var string
	 */
	protected $type;

	/**
	 * Current file we're dealing with
	 * 
	 * @var string
	 */
	protected $file;

	/**
	 * Target mime-type we're gonna deal with
	 * 
	 * @var string
	 */
	protected $mime;

	/**
	 * State initialization
	 * 
	 * @param string $file Image file
	 * @throws \RuntimeException If can't create image from provided $file
	 * @return void
	 */
	public function __construct($file)
	{
		if (!$this->load($file)) {
			throw new RuntimeException(sprintf('Can not load image from "%s"', $file));
		}

		$this->file = $file;
	}

	/**
	 * Destructor
	 * 
	 * @return void
	 */
	public function __destruct()
	{
		$this->done();
	}

	/**
	 * Returns current mime type
	 * 
	 * @return string
	 */
	public function getMime()
	{
		return $this->mime;
	}

	/**
	 * Returns current image MIME-type
	 * 
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Returns current image's width
	 * 
	 * @return float|integer
	 */
	public function getWidth()
	{
		return $this->width;
	}

	/**
	 * Returns current image's height
	 * 
	 * @return float|integer
	 */
	public function getHeight()
	{
		return $this->height;
	}

	/**
	 * Returns image's resource
	 * 
	 * @return resource
	 */
	public function getImage()
	{
		return $this->image;
	}

	/**
	 * Returns image info
	 * 
	 * @param string $file Path to the image from either URL or a filesystem
	 * @return array|boolean False on failure
	 */
	final protected function getImageInfo($file)
	{
		// @ - intentionally, to avoid notices
		$image = @getimagesize($file);

		if ($image !== false) {

			$data = array();
			$data['width'] = $image[0];
			$data['height'] = $image[1];
			$data['type'] = $image[2];
			$data['mime'] = $image['mime'];

			return $data;

		} else {

			return false;
		}
	}

	/**
	 * Loads an image into the memory from a file-system or URL
	 * 
	 * @param string $file
	 * @throws \LogicException When attempting to create from unsupported format
	 * @return resource
	 */
	final protected function createImageFromFile($file, $type)
	{
		switch ($type) {
			case \IMAGETYPE_GIF:
				return imagecreatefromgif($file);

			case \IMAGETYPE_JPEG:
				return imagecreatefromjpeg($file);

			case \IMAGETYPE_PNG:
				return imagecreatefrompng($file);
	
			default:
				throw new LogicException(sprintf('Can not create image from "%s"', $file));
		}
	}

	/**
	 * Frees the memory from loaded image
	 * 
	 * @return void
	 */
	final protected function free()
	{
		if (is_resource($this->image)) {
			return imagedestroy($this->image);
		}
	}

	/**
	 * Sets/overrides image's resource
	 * 
	 * @param resource $image
	 * @return void
	 */
	final protected function setImage($image)
	{
		// Before setting a new one we have to free memory first
		$this->free();
		$this->image = $image;
	}

	/**
	 * Loads the image into the memory
	 * 
	 * @param string $file
	 * @return boolean
	 */
	final protected function load($file)
	{
		$info = $this->getImageInfo($file);

		if ($info !== false) {

			$this->image = $this->createImageFromFile($file, $info['type']);

			$this->width = $info['width'];
			$this->height = $info['height'];
			$this->type = $info['type'];
			$this->mime = $info['mime'];

			return true;

		} else {

			return false;
		}
	}

	/**
	 * Cleans taken memory
	 * This should be called when work is done
	 * 
	 * @return void
	 */
	final public function done()
	{
		$this->free();
	}

	/**
	 * Saves an image to a file
	 * 
	 * @param string $path Full absolute path on the file system to save the image
	 * @param integer $quality Image quality Medium quality by default
	 * @param string $format Can be optionally saved in another format
	 * @return boolean
	 */
	final public function save($path, $quality = 75, $type = null)
	{
		// If no optional type is provided, then use current one
		if ($type === null) {
			$type = $this->type;
		}

		switch ($type) {
			case \IMAGETYPE_GIF:
				$result = imagegif($this->image, $path);
			break;

			case \IMAGETYPE_JPEG:
				$result = imagejpeg($this->image, $path, $quality);
			break;

			case \IMAGETYPE_PNG:
				$result = imagepng($this->image, $path, 9);
			break;

			default:
				throw new LogicException(sprintf(
					'Can not save image format (%s) to %s', $type, $path
				));
		}

		$this->done();

		// Returns boolean value indicating success or failure
		return $result;
	}

	/**
	 * Reloads the image
	 * 
	 * @return boolean
	 */
	final public function reload()
	{
		return $this->load($this->file);
	}

	/**
	 * Renders the image in a browser directly
	 * 
	 * @param integer $quality Image quality
	 * @return void
	 */
	final public function render($quality = 75)
	{
		header("Content-type: ".image_type_to_mime_type($this->type));

		switch ($this->type) {
			case \IMAGETYPE_GIF:
				imagegif($this->image, null);
			break;

			case \IMAGETYPE_JPEG:
				imagejpeg($this->image, null, $quality);
			break;

			case \IMAGETYPE_PNG:
				imagepng($this->image, null, 9);
			break;

			default:
				throw new LogicException(sprintf('Can not create image from "%s"', $this->file));
		}

		$this->done();
		exit(1);
	}

	/**
	 * We need this for GIFs and PNGs
	 * 
	 * @param resource $image
	 * @return void
	 */
	final protected function preserveTransparency($image)
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
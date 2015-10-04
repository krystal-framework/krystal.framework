<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Image\Processor\GD;

use RuntimeException;

if (!extension_loaded('gd')) {
	throw new RuntimeException('Image processor requires GD library');
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
	 * Required memory space for loaded image in bytes
	 * 
	 * @var integer
	 */
	protected $requiredMemorySpace;

	/**
	 * State initialization
	 * 
	 * @param string $file Image file
	 * @throws \RuntimeException If can't create image from provided $file
	 * @return void
	 */
	final public function __construct($file)
	{
		if (!$this->load($file)) {
			throw new RuntimeException(sprintf('Can not load image from %s', $file));
		}

		$this->file = $file;
	}

	/**
	 * Destructor
	 * 
	 * @return void
	 */
	final public function __destruct()
	{
		$this->done();
	}

	/**
	 * Returns required space for loaded image
	 * 
	 * @return integer
	 */
	final public function getrequiredMemorySpace()
	{
		return $this->requiredMemorySpace;
	}

	/**
	 * Returns current mime type
	 * 
	 * @return string
	 */
	final public function getMime()
	{
		return $this->mime;
	}

	/**
	 * Returns current image MIME-type
	 * 
	 * @return string
	 */
	final public function getType()
	{
		return $this->type;
	}

	/**
	 * Returns current image's width
	 * 
	 * @return float|integer
	 */
	final public function getWidth()
	{
		return $this->width;
	}

	/**
	 * Returns current image's height
	 * 
	 * @return float|integer
	 */
	final public function getHeight()
	{
		return $this->height;
	}

	/**
	 * Returns image's resource
	 * 
	 * @return resource
	 */
	final public function &getImage()
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

			return array(
				'width' => $image[0],
				'height' => $image[1],
				'type' => $image[2],
				'mime' => $image['mime'],
				'bits' => $image['bits']
			);

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

			// Calculate required memory space in bytes
			$this->requiredMemorySpace = $info['width'] * $info['height'] * $info['bits'];

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
	 * @throws \LogicException if can't save to the target format
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
	 * @throws \LogicException If can't render from the target image's type
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
}

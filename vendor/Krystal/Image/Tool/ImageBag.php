<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Image\Tool;

use RuntimeException;

/**
 * ImageBag should be usually placed inside another bag
 */
final class ImageBag implements ImageBagInterface
{
	/**
	 * Image path builder
	 * 
	 * @var \Krystal\Image\Tool\LocationBuilderInterface
	 */
	private $locationBuilder;

	/**
	 * Target id
	 * 
	 * @var string
	 */
	private $id;

	/**
	 * Basename of a cover image (For example: foo.jpg)
	 * 
	 * @var string
	 */
	private $cover;

	/**
	 * State initialization
	 * 
	 * @param \Krystal\Image\Tool\LocationBuilderInterface $locationBuilder
	 * @return void
	 */
	public function __construct(LocationBuilderInterface $locationBuilder)
	{
		$this->locationBuilder = $locationBuilder;
	}

	/**
	 * Checks whether both id and cover are provided
	 * 
	 * @return boolean
	 */
	private function isProvided()
	{
		return $this->cover !== null && $this->id !== null;
	}

	/**
	 * Defines a target id
	 * 
	 * @param string $id
	 * @return void
	 */
	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * Defines a basename of a cover
	 * 
	 * @param string $cover
	 * @return void
	 */
	public function setCover($cover)
	{
		$this->cover = $cover;
		return $this;
	}

	/**
	 * Returns image path on the file-system filtered by provided size
	 * 
	 * @param string $size
	 * @throws RuntimeException when not ready to be used
	 * @return string
	 */
	public function getPath($size)
	{
		if ($this->isProvided()) {
			return $this->locationBuilder->buildPath($this->id, $this->cover, $size);
		} else {
			throw new RuntimeException('You gotta provide both id and cover to use this method');
		}
	}

	/**
	 * Returns image URL filtered by provided size
	 * 
	 * @param string $size
	 * @throws RuntimeException when not ready to be used
	 * @return string
	 */
	public function getUrl($size)
	{
		if ($this->isProvided()) {
			return $this->locationBuilder->buildUrl($this->id, $this->cover, $size);
		} else {
			throw new RuntimeException('You gotta provide both id and cover to use this method');
		}
	}
}

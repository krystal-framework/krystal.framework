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

final class LocationBuilder implements LocationBuilderInterface
{
	/**
	 * Target directory we're working with
	 * 
	 * @var string
	 */
	private $baseDir;

	/**
	 * Base url
	 * 
	 * @var string
	 */
	private $baseUrl;

	/**
	 * Target path we're dealing with 
	 * 
	 * @var string
	 */
	private $path;

	/**
	 * State initialization
	 * 
	 * @param string $baseDir
	 * @param string $baseUrl
	 * @param string $path
	 * @return void
	 */
	public function __construct($baseDir, $baseUrl, $path)
	{
		$this->baseDir = $baseDir;
		$this->baseUrl = $baseUrl;
		$this->path = $path;
	}

	/**
	 * Provides a path
	 * 
	 * @param string $target
	 * @param string $id
	 * @param string $image
	 * @param string $dimension
	 * @return string
	 */
	private function provide($target, $id, $image, $dimension)
	{
		return sprintf('%s/%s/%s/%s/%s', $target, $this->path, $id, $image, $dimension);
	}

	/**
	 * Normalizes URL path
	 * 
	 * @param string $url
	 * @return string
	 */
	private function normalizeUrl($url)
	{
		return str_replace(array('///'), '/', $url);
	}

	/**
	 * Build a path to the image on the filesystem
	 * 
	 * @param string $id
	 * @param string $image
	 * @param string $dimension
	 * @return string
	 */
	public function buildPath($id, $image, $dimension)
	{
		return $this->provide($this->baseDir, $id, $dimension, $image);
	}

	/**
	 * Builds an URL to the image
	 * 
	 * @param string $id
	 * @param string $image
	 * @param string $dimension
	 * @return string
	 */
	public function buildUrl($id, $image, $dimension)
	{
		$url = $this->provide($this->baseUrl, $id, $dimension, $image);
		return $this->normalizeUrl($url);
	}
}

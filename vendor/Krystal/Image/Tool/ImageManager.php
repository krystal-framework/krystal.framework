<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Image\Tool;

use Krystal\Image\Tool\Upload\UploaderFactory;
use Krystal\Filesystem\FileManager;

final class ImageManager implements ImageManagerInterface
{
	/**
	 * Root directory
	 * 
	 * @var string
	 */
	private $rootDir;

	/**
	 * Root URL
	 * 
	 * @var string
	 */
	private $rootUrl;

	/**
	 * Common shared path which is the same for $rootUrl and $rootDir
	 * 
	 * @var string
	 */
	private $path;

	/**
	 * Uploader plugins
	 * 
	 * @var array
	 */
	private $plugins = array();

	/**
	 * Image data container
	 * 
	 * @var \Krystal\Image\ImageBag
	 */
	private $imageBag;

	/**
	 * File handler for images
	 * 
	 * @var \Krystal\Image\FileHandler
	 */
	private $fileHandler;

	/**
	 * Image-file specific uploader
	 * 
	 * @var \Krystal\Http\FileTransfer\UploadChain
	 */
	private $uploader;

	/**
	 * State initialization
	 * 
	 * @param string $path
	 * @param string $rootDir Root system directory
	 * @param string $rootUrl Root system URL
	 * @param array $plugins
	 * @return void
	 */
	public function __construct($path, $rootDir, $rootUrl, array $plugins)
	{
		$this->path = $path;
		$this->rootDir = $rootDir;
		$this->rootUrl = $rootUrl;
		$this->plugins = $plugins;
	}

	/**
	 * Returns prepared upload chain
	 * 
	 * @return \Krystal\Http\FileTransfer\UploadChain
	 */
	private function getUploader()
	{
		if (is_null($this->uploader)) {
			$this->uploader = UploaderFactory::build($this->rootDir.$this->path, $this->plugins);
		}
		
		return $this->uploader;
	}

	/**
	 * Returns prepared FileHandler instance
	 * 
	 * @return \Krystal\Image\FileHandler
	 */
	private function getFileHandler()
	{
		if (is_null($this->fileHandler)) {
			$path = rtrim($this->path, '/');
			$this->fileHandler = new FileHandler($this->rootDir.$path, new FileManager());
		}
		
		return $this->fileHandler;
	}

	/**
	 * Returns prepared ImageBag instance
	 * 
	 * @return \Krystal\Image\ImageBag
	 */
	public function getImageBag()
	{
		if ($this->imageBag == null) {
			$this->imageBag = new ImageBag(new LocationBuilder($this->rootDir, $this->rootUrl, $this->path));
		}

		return $this->imageBag;
	}

	/**
	 * Uploads an image from $files
	 * 
	 * @param string $id
	 * @param array $files Files collection
	 * @return boolean
	 */
	public function upload($id, array $files)
	{
		return $this->getUploader()->upload($id, $files);
	}

	/**
	 * Deletes a directory by its id
	 * 
	 * @param string $id
	 * @param string $image Optional image filter
	 * @return boolean
	 */
	public function delete($id, $image = null)
	{
		return $this->getFileHandler()->delete($id, $image);
	}
}

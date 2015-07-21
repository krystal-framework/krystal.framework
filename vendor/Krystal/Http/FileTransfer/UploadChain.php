<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\FileTransfer;

final class UploadChain implements ChainInterface
{
	/**
	 * Collection of uploaders
	 * 
	 * @var array
	 */
	private $uploaders = array();

	/**
	 * State initialization
	 * 
	 * @param array $uploaders
	 * @return void
	 */
	public function __construct(array $uploaders = array())
	{
		if (!empty($uploaders)) {
			$this->addUploaders($uploaders);
		}
	}

	/**
	 * Adds an uploader that implements UploaderAwareInterface
	 * 
	 * @param \Krystal\Http\FileTransfer\UploaderAwareInterface $uploader
	 * @return \Krystal\Http\FileTransfer\UploadChain
	 */
	public function addUploader(UploaderAwareInterface $uploader)
	{
		$this->uploaders[] = $uploader;
		return $this;
	}

	/**
	 * Add more uploaders
	 * 
	 * @param array $uploaders An array of \Krystal\Http\FileTransfer\UploaderAwareInterface instances
	 * @return \Krystal\Http\FileTransfer\UploadChain
	 */
	public function addUploaders(array $uploaders)
	{
		foreach ($uploaders as $uploader) {
			$this->addUploader($uploader);
		}

		return $this;
	}

	/**
	 * Uploads via all defined uploaders
	 * 
	 * @param string $id Nested directory's id
	 * @param array $files An array of file entities
	 * @return boolean
	 */
	public function upload($id, array $files)
	{
		foreach ($this->uploaders as $uploader) {
			if (!$uploader->upload($id, $files)) {
				return false;
			}
		}

		return true;
	}
}

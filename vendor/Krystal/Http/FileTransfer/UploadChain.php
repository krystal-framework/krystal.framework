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

use Krystal\Http\FileTransfer\Uploader;

final class UploadChain implements ChainInterface
{
	/**
	 * Target uploaders
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
	 * @param UploaderAwareInterface $uploader
	 * @return UploadChain
	 */
	public function addUploader(UploaderAwareInterface $uploader)
	{
		$this->uploaders[] = $uploader;
		return $this;
	}

	/**
	 * Add more uploaders
	 * 
	 * @param array $uploaders
	 * @return UploadChain
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
	 * @param string $id
	 * @param array $files
	 * @return void
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

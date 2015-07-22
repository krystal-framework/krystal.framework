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

use Krystal\Filesystem\FileManager;

final class DirectoryBag implements DirectoryBagInterface
{
	/**
	 * Base directory
	 * 
	 * @var string
	 */
	private $baseDir;

	/**
	 * State initialization
	 * 
	 * @param string $baseDir
	 * @return void
	 */
	public function __construct($baseDir)
	{
		$this->baseDir = $baseDir;
	}

	/**
	 * Returns a path to a file in the target directory
	 * 
	 * @param string $id Nested directory's id
	 * @param string $file A filename inside that nested directory
	 * @return string
	 */
	public function getPath($id, $file = null)
	{
		if (is_null($file)) {
			return sprintf('%s/%s/', $this->baseDir, $id);
		} else {
			return sprintf('%s/%s/%s', $this->baseDir, $id, $file);
		}
	}

	/**
	 * Uploads a file
	 * 
	 * @param string $id Nested directory's id
	 * @param array $files An array of file bags
	 * @return boolean
	 */
	public function upload($id, array $files)
	{
		if (!empty($files)) {
			$uploader = new FileUploader();

			foreach ($files as $file) {
				if (!$uploader->upload($this->getPath($id), $files)) {
					return false;
				}
			}

			return true;
		}

		return false;
	}

	/**
	 * Removes a directory by its nested id
	 * Or removes a file insider that nested directory by its nested filename
	 * 
	 * @param string $id Nested directory's id
	 * @param string $filename Filename to be removed. Optional
	 * @return boolean
	 */
	public function remove($id, $filename = null)
	{
		$fm = new FileManager();

		if ($filename == null) {
			return $fm->rmdir($this->getPath($id));

		} else {
			return $fm->rmfile($this->getPath($id, $filename));
		}
	}
}

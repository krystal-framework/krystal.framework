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

use Krystal\Http\FileTransfer\FileInfo;
use LogicException;

final class FileUploader implements FileUploaderInterface
{
	/**
	 * Whether to override on collision
	 * 
	 * @var boolean
	 */
	private $override;

	/**
	 * Whether a directory should be created when doesn't exist
	 * 
	 * @var boolean
	 */
	private $destinationAutoCreate;

	/**
	 * State initialization
	 * 
	 * @param boolean $override Whether to override on collision
	 * @param boolean $destinationAutoCreate
	 * @return void
	 */
	public function __construct($override = true, $destinationAutoCreate = true)
	{
		$this->override = (bool) $override;
		$this->destinationAutoCreate = (bool) $destinationAutoCreate;
	}

	/**
	 * Upload files from the input
	 * 
	 * @param string $destination
	 * @param array $files
	 * @return boolean
	 */
	public function upload($destination, array $files)
	{
		foreach ($files as $file) {
			if (!($file instanceof FileInfo)) {
				// This should never occur, but in theory we have to do that because of framework users
				throw new LogicException();
			}

			// Gotta ensure again, UPLOAD_ERR_OK means there are no errors
			if ($file->getError() == \UPLOAD_ERR_OK) {
				// Start trying to move a file
				if (!$this->move($destination, $file->getTmpName(), $file->getName())) {
					return false;
				}
			} else {
				// Invalid file, so that cannot be uploaded. That actually should be reported before inside validator
				return false;
			}
		}

		return true;
	}

	/**
	 * Moves a singular file
	 * 
	 * @param string $destination
	 * @param string $tmp
	 * @param string $filename
	 * @return boolean Depending on success
	 */
	private function move($destination, $tmp, $filename)
	{
		if (!is_dir($destination)) {
			// We can either create it automatically
			if ($this->destinationAutoCreate === true) {
				// Then make a directory (recursively if needed)
				@mkdir($destination, 0777, true);
				
			} else {
				// Destination doesn't exist, and we shouldn't be creating it
				throw new RuntimeException(sprintf(
					'Destination folder does not exist', $destination
				));
			}
		}

		$target = sprintf('%s/%s', $destination, $filename);

		// If Remote file exists and we don't want to override it, so let's stop here
		if (is_file($target)) {
			if (!$this->override) {
				return true;
			}
		}

		return move_uploaded_file($tmp, $target);
	}
}

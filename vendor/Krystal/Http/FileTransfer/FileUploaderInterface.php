<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\FileTransfer;

interface FileUploaderInterface
{
	/**
	 * Upload files from the input
	 * 
	 * @param string $destination
	 * @param array $files
	 * @return boolean
	 */
	public function upload($destination, array $files);
}

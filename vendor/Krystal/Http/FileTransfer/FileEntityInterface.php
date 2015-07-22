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

interface FileEntityInterface
{
	/**
	 * Defines type for a file
	 * 
	 * @param string $type
	 * @return \Krystal\Http\FileTransfer\FileEntity
	 */
	public function setType($type);
	
	/**
	 * Returns file type
	 * 
	 * @return string
	 */
	public function getType();
	
	/**
	 * Defines a name
	 * 
	 * @param string $name
	 * @return \Krystal\Http\FileTransfer\FileEntity
	 */
	public function setName($name);
	
	/**
	 * Returns a name
	 * 
	 * @return string
	 */
	public function getName();
	
	/**
	 * Defines a temporary name
	 * 
	 * @param string $tmpName
	 * @return \Krystal\Http\FileTransfer\FileEntity
	 */
	public function setTmpName($tmpName);
	
	/**
	 * Returns temporary location
	 * 
	 * @return string
	 */
	public function getTmpName();
	
	/**
	 * Defines an error
	 * 
	 * @param string $error
	 * @return \Krystal\Http\FileTransfer\FileEntity
	 */
	public function setError($error);
	
	/**
	 * Returns error message
	 * 
	 * @return string
	 */
	public function getError();

	/**
	 * Defines a size
	 * 
	 * @param string $size
	 * @return \Krystal\Http\FileTransfer\FileEntity
	 */
	public function setSize($size);

	/**
	 * Returns file size
	 * 
	 * @return string
	 */
	public function getSize();
}

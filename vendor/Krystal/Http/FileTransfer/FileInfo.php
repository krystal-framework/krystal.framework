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

use Krystal\Http\FileTransfer\FileInfoInterface;

//@TODO: rename to FileEntity
final class FileInfo implements FileInfoInterface
{
	/**
	 * Detected MIMI-type
	 * 
	 * @var string
	 */
	private $type;

	/**
	 * Original file name
	 * 
	 * @var string
	 */
	private $name;

	/**
	 * Auto-generated path to temporary file
	 * 
	 * @var string
	 */
	private $tmpName;

	/**
	 * Error code if present
	 * 
	 * @var string
	 */
	private $error;

	/**
	 * File size in bytes
	 * 
	 * @var integer
	 */
	private $size;
	
	/**
	 * Defines type for a file
	 * 
	 * @param string $type
	 * @return object $this
	 */
	public function setType($type)
	{
		$this->type = $type;
		return $this;
	}

	/**
	 * Returns file type
	 * 
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Defines a name
	 * 
	 * @param string $name
	 * @return object $this
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * Returns a name
	 * 
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Defines a temporary name
	 * 
	 * @param string $tmpName
	 * @return object $this
	 */
	public function setTmpName($tmpName)
	{
		$this->tmpName = $tmpName;
		return $this;
	}

	/**
	 * Returns temporary location
	 * 
	 * @return string
	 */
	public function getTmpName()
	{
		return $this->tmpName;
	}

	/**
	 * Defines an error
	 * 
	 * @param string $error
	 * @return object $this
	 */
	public function setError($error)
	{
		$this->error = $error;
		return $this;
	}

	/**
	 * Returns error message
	 * 
	 * @return string
	 */
	public function getError()
	{
		return $this->error;
	}

	/**
	 * Defines a size
	 * 
	 * @param string $size
	 * @return object $this
	 */
	public function setSize($size)
	{
		$this->size = $size;
		return $this;
	}

	/**
	 * Returns file size
	 * 
	 * @return string
	 */
	public function getSize()
	{
		return $this->size;
	}
}

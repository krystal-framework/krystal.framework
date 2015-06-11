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

// TODO: rename to FileBag
final class FileInfo implements FileInfoInterface
{
	/**
	 * Files reference
	 * 
	 * @var array
	 */
	private $container = array();

	/**
	 * Defines type for a file
	 * 
	 * @param string $type
	 * @return object $this
	 */
	public function setType($type)
	{
		$this->container['type'] = $type;
		return $this;
	}

	/**
	 * Returns file type
	 * 
	 * @return string
	 */
	public function getType()
	{
		return $this->container['type'];
	}

	/**
	 * Defines a name
	 * 
	 * @param string $name
	 * @return object $this
	 */
	public function setName($name)
	{
		$this->container['name'] = $name;
		return $this;
	}

	/**
	 * Returns a name
	 * 
	 * @return string
	 */
	public function getName()
	{
		return $this->container['name'];
	}

	/**
	 * Defines a temporary name
	 * 
	 * @param string $tmpName
	 * @return object $this
	 */
	public function setTmpName($tmpName)
	{
		$this->container['tmpName'] = $tmpName;
		return $this;
	}

	/**
	 * Returns temporary location
	 * 
	 * @return string
	 */
	public function getTmpName()
	{
		return $this->container['tmpName'];
	}

	/**
	 * Defines an error
	 * 
	 * @param string $error
	 * @return object $this
	 */
	public function setError($error)
	{
		$this->container['error'] = $error;
		return $this;
	}

	/**
	 * Returns error message
	 * 
	 * @return string
	 */
	public function getError()
	{
		return $this->container['error'];
	}

	/**
	 * Defines a size
	 * 
	 * @param string $size
	 * @return object $this
	 */
	public function setSize($size)
	{
		$this->container['size'] = $size;
		return $this;
	}

	/**
	 * Returns file size
	 * 
	 * @return string
	 */
	public function getSize()
	{
		return $this->container['size'];
	}
}

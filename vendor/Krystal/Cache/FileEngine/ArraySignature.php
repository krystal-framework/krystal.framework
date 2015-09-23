<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Cache\FileEngine;

final class ArraySignature implements ArraySignatureInterface
{
	/**
	 * Initial array signature
	 * 
	 * @var string
	 */
	private $initial;

	/**
	 * Sets the initial data
	 * 
	 * @param array $data
	 * @return void
	 */
	public function setData(array $data)
	{
		$this->initial = $this->make($data);
	}

	/**
	 * Checks new hash against its initial one
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function hasChanged(array $data)
	{
		return $this->make($data) !== $this->initial;
	}

	/**
	 * Makes a signature of an array
	 * 
	 * @param array $array
	 * @return string
	 */
	private function make(array $array)
	{
		return md5(serialize($array));
	}
}

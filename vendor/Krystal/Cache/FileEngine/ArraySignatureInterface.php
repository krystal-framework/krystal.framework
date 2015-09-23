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

interface ArraySignatureInterface
{
	/**
	 * Sets the initial data
	 * 
	 * @param array $data
	 * @return void
	 */
	public function setData(array $data);

	/**
	 * Checks new hash against its initial one
	 * 
	 * @param array $data
	 * @return boolean
	 */
	public function hasChanged(array $data);
}

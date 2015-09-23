<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Filter;

interface QueryGeneratorInterface
{
	/**
	 * Generates URL
	 * 
	 * @param array $data
	 * @return string
	 */
	public function generate(array $data);
}

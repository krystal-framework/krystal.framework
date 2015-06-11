<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\MySQL;

interface TableBuilderInterface
{
	/**
	 * Loads data from file
	 * 
	 * @param string $filename
	 * @throws InvalidArgumentException if $filename isn't string
	 * @return void
	 */
	public function loadFromFile($filename);
	
	/**
	 * Build tables
	 * 
	 * @return boolean Depending on success
	 */
	public function run();
}

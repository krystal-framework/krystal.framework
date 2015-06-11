<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Config\File;

final class ArrayAdapter
{
	/**
	 * {@inheritDoc}
	 */
	public function read($file)
	{
		return require($file);
	}

	/**
	 * {@inheritDoc}
	 */
	public function write($data)
	{
		return "<?php\n return ".var_export($data, true).';';
	}
}

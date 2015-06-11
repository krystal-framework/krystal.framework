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

use LogicException;
//use Krystal\Config\Adapter\ConfigAdapterInterface;

final class FileArray extends FileAbstract// implements ConfigAdapterInterface
{
	/**
	 * Renders an array representation into a string
	 * 
	 * @return string
	 */
	protected function render()
	{
		return "<?php\n return ".var_export($this->config, true).';';
	}

	/**
	 * Fetches configuration from a file
	 * 
	 * @throws \LogicException if included file didn't return an array
	 * @return array
	 */
	protected function fetch()
	{
		$array = require($this->path);
		
		if (!is_array($array)) {
			throw new LogicException('A file must return an array');
		} else {
			return $array;
		}
	}
}

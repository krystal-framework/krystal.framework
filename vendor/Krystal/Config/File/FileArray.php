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

final class FileArray extends FileAbstract
{
	/**
	 * {@inheritDoc}
	 */
	protected function render()
	{
		return "<?php\n return ".var_export($this->config, true).';';
	}

	/**
	 * {@inheritDoc}
	 */
	protected function fetch()
	{
		$array = require($this->path);

		if (!is_array($array)) {
			throw new LogicException(sprintf('Configuration file "%s" must return an array', $this->path));
		} else {
			return $array;
		}
	}
}

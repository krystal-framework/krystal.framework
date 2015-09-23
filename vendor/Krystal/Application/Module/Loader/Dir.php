<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Module\Loader;

use DirectoryIterator;
use RuntimeException;

final class Dir implements LoaderInterface
{
	/**
	 * Path to the module directory
	 * 
	 * @var string
	 */
	private $dir;

	/**
	 * State initialization
	 * 
	 * @param string $dir Module directory
	 * @return void
	 */
	public function __construct($dir)
	{
		$this->dir = $dir;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getModules()
	{
		if (!is_dir($this->dir)) {
			throw new RuntimeException(sprintf('Invalid directory provided "%s"', $this->dir));
		}

		$iterator = new DirectoryIterator($this->dir);
		$result = array();

		foreach ($iterator as $file) {
			if (!$file->isDot() && $file->isDir()) {
				array_push($result, $file->getBaseName());
			}
		}

		return $result;
	}
}

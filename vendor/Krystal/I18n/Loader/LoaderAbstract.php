<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\I18n\Loader;

abstract class LoaderAbstract
{
	/**
	 * @var array
	 */
	protected $stack = array();

	/**
	 * Load all arrays from a directory
	 * 
	 * @param string $dir
	 * @return void
	 */
	public function loadAllFromDir($dir)
	{
		$iterator = new DirectoryIterator($dir);
		
		foreach ($iterator as $file) {
			if ($file->isFile() && !$file->isDot()) {
				
				$filename = sprintf('%s/%s', $file->getPath(), $file->getFilename());
				$this->loadFromFile($filename);
			}
		}
	}

	/**
	 * Returns loaded data
	 * 
	 * @return array
	 */
	public function getData()
	{
		return $this->stack;
	}

	/**
	 * Validate included data
	 * 
	 * @param array $data
	 * @return boolean Depending on success
	 */
	protected function isValid($data)
	{
		return is_array($data);
	}

	/**
	 * Appends one or more elements to the stack
	 * 
	 * @param array $array
	 * @return void
	 */
	protected function pushToStack(array $array)
	{
		foreach ($array as $key => $value) {
			$this->stack[$key] = $value;
		}
	}
}

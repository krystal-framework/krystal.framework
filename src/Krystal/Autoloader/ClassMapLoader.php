<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Autoloader;

/* The autoloader is not ready yet*/
require_once(__DIR__ . '/AbstractSplLoader.php');

final class ClassMapLoader extends AbstractSplLoader
{
	/**
	 * Class name => Class location map
	 * 
	 * @var array
	 */
	private $map = array();

	/**
	 * State initialization
	 * 
	 * @param array $map
	 * @return void
	 */
	public function __construct(array $map)
	{
		$this->map = $map;
	}

	/**
	 * {@inheritDoc}
	 */
	public function loadClass($class)
	{
		$file = $this->getPathByClassName($class);
		return $this->includeClass($file . self::EXTENSTION);
	}

	/**
	 * Returns path associated with given class name
	 * 
	 * @param string $class
	 * @return string
	 */
	private function getPathByClassName($class)
	{
		if (isset($this->map[$class])) {
			return $this->map[$class];
		} else {
			return null;
		}
	}

	/**
	 * Returns associated class with given
	 * 
	 * @param string $path
	 * @return string
	 */
	private function getClassNameByPath($path)
	{
		foreach ($this->map as $key => $value) {
			if ($value === $path) {
				return $key;
			}
		}
		
		return null;
	}
}

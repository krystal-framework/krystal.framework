<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\View;

use LogicException;

final class BlockBag implements BlockBagInterface
{
	/**
	 * Static block collection
	 * 
	 * @var array
	 */
	private $blocks = array();

	/**
	 * Available block directories
	 * 
	 * @var string
	 */
	private $blockDir;

	/**
	 * Static blocks
	 * 
	 * @var array
	 */
	private $staticBlocks = array();

	/**
	 * State initialization
	 * 
	 * @param string $extension Default is phtml
	 * @return void
	 */
	public function __construct($extension = 'phtml')
	{
		$this->extension = $extension;
	}

	/**
	 * Attempts to return block's file path
	 * 
	 * @param string $name Block's name
	 * @throws \LogicException If can't find a block's file by its name
	 * @return string
	 */
	public function getBlockFile($name)
	{
		$file = $this->getPathWithBaseDir($this->getBlocksDir(), $name);

		if (is_file($file)) {
			return $file;

		} else if ($this->hasStaticBlock($name)) {
			return $this->getStaticFile($name);

		} else {
			throw new LogicException(sprintf('Could not find a registered block called %s', $name));
		}
	}

	/**
	 * Returns a path with base directory
	 * 
	 * @param string $baseDir
	 * @param string $name
	 * @return string
	 */
	private function getPathWithBaseDir($baseDir, $name)
	{
		return sprintf('%s/%s.%s', $baseDir, $name, $this->extension);
	}

	/**
	 * Adds new static block to collection
	 * 
	 * @param string $name
	 * @param string $baseDir
	 * @throws \LogicException if wrong data supplied
	 * @return \Krystal\Application\View\BlockBag
	 */
	public function addStaticBlock($baseDir, $name)
	{
		$file = $this->getPathWithBaseDir($baseDir, $name);

		if (!is_file($file)) {
			throw new LogicException(sprintf('Invalid base directory or file name provided "%s"', $file));
		}

		$this->staticBlocks[$name] = $file;
	}

	/**
	 * Checks whether static block has been added before
	 * 
	 * @param string $name
	 * @return boolean
	 */
	private function hasStaticBlock($name)
	{
		$names = array_keys($this->staticBlocks);
		return in_array($name, $names);
	}
	
	/**
	 * Returns path to a static file
	 * 
	 * @param string $name Block's name
	 * @return string
	 */
	private function getStaticFile($name)
	{
		return $this->staticBlocks[$name];
	}

	/**
	 * Returns block directory path
	 * 
	 * @return string
	 */
	public function getBlocksDir()
	{
		return $this->blockDir;
	}

	/**
	 * Defines block directory path
	 * 
	 * @param string $blockDir
	 * @return \Krystal\Application\View\BlockBag
	 */
	public function setBlocksDir($blockDir)
	{
		$this->blockDir = $blockDir;
		return $this;
	}

	/**
	 * Registers a block
	 * 
	 * @param string $name
	 * @param string $path
	 * @return \Krystal\Application\View\BlockBag
	 */
	public function registerBlock($name, $path)
	{
		$this->blocks[$name] = $path;
		return $this;
	}

	/**
	 * Register blocks
	 * 
	 * @param array $blocks
	 * @return \Krystal\Application\View\BlockBag
	 */
	public function registerBlocks(array $blocks)
	{
		foreach ($blocks as $name => $path) {
			$this->registerBlock($name, $path);
		}
		
		return $this;
	}
}

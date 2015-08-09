<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Filesystem;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use DirectoryIterator;
use RuntimeException;

class FileManager
{
	/**
	 * Returns base name of a file
	 * 
	 * @param string $file
	 * @return string
	 */
	public function getBaseName($file)
	{
		return pathinfo($file, \PATHINFO_BASENAME);
	}

	/**
	 * Fetches file extension
	 * 
	 * @param string $target
	 * @return string
	 */
	public function getExtension($file)
	{
		return pathinfo($file, \PATHINFO_EXTENSION);
	}

	/**
	 * Fetches mime type
	 * 
	 * @param string $file
	 * @return string
	 */
	public function getMimeType($file)
	{
		$mimeType = new MimeTypeGuesser();
		$extension = $this->getExtension($file);
		
		return $mimeType->getTypeByExtension($file);
	}

	/**
	 * Safely removes a file
	 * 
	 * @param string $file
	 * @throws RuntimeException When invalid file provided
	 * @return boolean
	 */
	public function rmfile($file)
	{
		if (is_file($file)) {
			return chmod($file, 0777) && unlink($file);
			
		} else {
			throw new RuntimeException(sprintf(
				'Invalid file provided "%s"', $file
			));
		}
	}

	/**
	 * Builds directory tree
	 * 
	 * @param string $dir
	 * @param boolean $self Whether to include $dir in result
	 * @throws \RuntimeException When invalid directory provided
	 * @return array
	 */
	public function getDirTree($dir, $self = false)
	{
		if (!is_dir($dir)) {
			throw new RuntimeException(sprintf(
				'Can not build directory tree because of invalid path "%s"', $dir
			));
		}

		$target = array();
		$tree = array();
		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir), RecursiveIteratorIterator::SELF_FIRST);

		if ($self !== false) {
			array_push($target, $dir);
		}

		foreach ($iterator as $file) {
			array_push ($target, $file);
		}

		foreach ($target as $index => $file) {
			array_push($tree, (string) $file);
		}

		return $tree;
	}

	/**
	 * Counts directory size in bytes
	 * 
	 * @param string $dir
	 * @return float
	 */
	public function getDirSizeCount($dir)
	{
		$count = 0.00;
		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
		
		foreach ($iterator as $file) {
			$count += $file->getSize(); 
		}
		
		return $count;
	}

	/**
	 * Removes everything in a directory but leaves directory itself
	 * 
	 * @param string $dir
	 * @return boolean Depending on success
	 */
	public function cleanDir($dir)
	{
		return $this->rmdir($dir) && mkdir($dir, 0777);
	}

	/**
	 * Recursively applies chmod to given directory
	 * 
	 * @param string $file
	 * @param integer $mode
	 * @param array	$ignored Items that unreadable or unaccessable
	 * @throws UnexpectedValueException if $file is neither a directory and a file
	 * @return boolean Depending on success
	 */
	public function chmod($file, $mode = 0777, array &$ignored = array())
	{
		if (is_file($file)) {
			if (!chmod($file, $mode)) {
				array_push($ignored, $file);
				return false;
			}
			
		} else if (is_dir($file)) {
			
			$items = $this->getDirTree($file, true);
			
			foreach ($items as $item) {
				if (!chmod($item, $mode)) {
					array_push($ignored, $item);
				}
			}
			
		} else {
			
			throw new UnexpectedValueException(sprintf(
				'%s expects a path to be a directory or a file as first argument', __METHOD__
			));
		}
		
		return true;
	}

	/**
	 * Remove a directory (recursively)
	 * 
	 * @param string $dir
	 * @param array $ignored Files that couldn't be read
	 * @throws \UnexcpectedValueException if $dir isn't a path to directory
	 * @return boolean Depending on success
	 */
	public function rmdir($dir, array &$ignored = array())
	{
		if (!is_dir($dir)) {
			throw new RuntimeException();
		}

		foreach (glob($dir . '/*') as $file) {
			if (is_dir($file)) {
				// Recursive call
				call_user_func(__METHOD__, $file);
			} else {
				// Coudn't unlink - push this ignored item
				chmod($file, 0777);

				if (!unlink($file)) {
					array_push($ignored, $file);
				}
			}
		}

		if (!rmdir($dir)) {
			array_push($ignored, $dir);
		}

		return true;
	}

	/**
	 * Copies a file to another directory
	 * 
	 * @param string $file The path to the file
	 * @param string $dir The dir file will be copied in
	 * @return boolean Depending on success
	 */
	public function copy($src, $dst)
	{
		$dir = opendir($src);
		
		if (!is_dir($dst)) {
			mkdir($dst, 0777);
		}
		
		while (false !== ($file = readdir($dir))) {
			// We must ensure a file isn't a dot
			if (($file != '.' ) && ($file != '..' )) {
				if (is_dir($src . '/' . $file)) {
					// Recursive call
					call_user_func(__METHOD__, $src . '/' . $file, $dst . '/' . $file); 
				} else {
					copy($src . '/' . $file, $dst . '/' . $file); 
				}
			}
		}
		
		closedir($dir);
		return true;
	}

	/**
	 * Moves a directory
	 * 
	 * @param string $file
	 * @param string $destination
	 * @return boolean
	 */
	public function move($from, $to)
	{
		if ($this->copy($from, $to)) {
			return $this->delete($from);
		}
	}

	/**
	 * Checks whether file is empty
	 * 
	 * @param string $filename
	 * @return boolean TRUE if $filename is empty
	 */
	public function isFileEmpty($filename)
	{
		return mb_strlen(file_get_contents($filename, 2), 'UTF-8') > 0 ? false : true;
	}

	/**
	 * Returns nested directories inside provided one
	 * 
	 * @param string $dir
	 * @return array
	 */
	public function getFirstLevelDirs($dir)
	{
		$iterator = new DirectoryIterator($dir);
		$result = array();
		
		foreach ($iterator as $item) {
			if (!$item->isDot()) {
				if ($item->isDir()) {
					array_push($result, $item->getFileName());
				}
			}
		}
		
		return $result;
	}

	/**
	 * Fetch directory files
	 * 
	 * @param string $dir
	 * @param boolean $recursion
	 * @return array
	 */
	public function getTreeFiles($dir, $recursion = true)
	{
		if ($recursion === true) {
			return $this->buildDirTree($dir);
		} else {
			
			$iterator = new DirectoryIterator($dir);
			$result = array();
			
			foreach ($iterator as $item) {
				if (!$item->isDot()) {
					if ($item->isFile() || $item->isDir()) {
						array_push($result, $item->getPathName());
					}
				}
			}
			
			return $result;
		}
	}

	/**
	 * Examine files inside a directory by taking advantage of native pathinfo() function
	 * 
	 * @param string $dir
	 * @param boolean $recursion
	 * @param integer $const One of these: PATHINFO_DIRNAME | PATHINFO_BASENAME | PATHINFO_EXTENSION | PATHINFO_FILENAME
	 * @return array
	 */
	public function fetchDirByPathInfo($dir, $const, $recursion = true)
	{
		$files = $this->fetchDirFiles($dir, $recursion);
		$result = array();
		
		foreach ($files as $file) {
			array_push($result, pathinfo($file, $const));
		}
		
		return $result;
	}

	/**
	 * Determines whether a directory is empty
	 * 
	 * @todo Add ignored extension and files, like Thumbs.db
	 * 
	 * @param string $dir Path to a directory
	 * @throws RuntimeException if $dir isn't a directory
	 * @return boolean TRUE if empty, FALSE if not
	 */
	public function isDirEmpty($dir)
	{
		// We don't need recursion here
		return count($this->fetchDirFiles($dir, false)) === 0;
	}
}

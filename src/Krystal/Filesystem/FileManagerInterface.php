<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Filesystem;

interface FileManagerInterface
{
    /**
     * Returns a directory name from a path
     * 
     * @param string $path
     * @return string
     */
    public function getBaseName($path);

    /**
     * Returns a directory name from a path
     * 
     * @param string $path
     * @return string
     */
    public function getExtension($path);

    /**
     * Returns a directory name from a path
     * 
     * @param string $path
     * @return string
     */
    public function getDirName($path);

    /**
     * Returns a file name from a path
     * 
     * @param string $path
     * @return string
     */
    public function getFileName($path);

    /**
     * Fetches mime type from a file
     * 
     * @param string $file
     * @return string
     */
    public function getMimeType($file);

    /**
     * Safely removes a file
     * 
     * @param string $file
     * @throws \RuntimeException When invalid file provided
     * @return boolean
     */
    public function rmfile($file);

    /**
     * Builds directory tree
     * 
     * @param string $dir
     * @param boolean $self Whether to include $dir in result
     * @throws \RuntimeException When invalid directory provided
     * @return array
     */
    public function getDirTree($dir, $self = false);

    /**
     * Counts directory size in bytes
     * 
     * @param string $dir
     * @throws \RuntimeException If invalid directory path supplied
     * @return float
     */
    public function getDirSizeCount($dir);

    /**
     * Removes everything in a directory but leaves directory itself
     * 
     * @param string $dir
     * @return boolean Depending on success
     */
    public function cleanDir($dir);

    /**
     * Recursively applies chmod to given directory
     * 
     * @param string $file
     * @param integer $mode
     * @param array $ignored Items that unreadable or accessible
     * @throws \UnexpectedValueException if $file is neither a directory and a file
     * @return boolean Depending on success
     */
    public function chmod($file, $mode, array &$ignored = array());

    /**
     * Removes a directory (recursively)
     * 
     * @param string $dir
     * @throws \RuntimeException if $dir isn't a path to directory
     * @return boolean Depending on success
     */
    public function rmdir($dir);

    /**
     * Copies a directory to another directory
     * 
     * @param string $file The path to the file
     * @param string $dir The dir file will be copied in
     * @throws \RuntimeException if $src isn't a path to directory
     * @return boolean Depending on success
     */
    public function copy($src, $dst);

    /**
     * Moves a directory
     * 
     * @param string $file Target directory
     * @param string $to Target destination path
     * @return boolean
     */
    public function move($from, $to);

    /**
     * Checks whether file is empty
     * 
     * @param string $file
     * @throws \RuntimeException if invalid file path supplied
     * @return boolean
     */
    public function isFileEmpty($file);

    /**
     * Returns nested directories inside provided one
     * 
     * @param string $dir
     * @throws \UnexpectedValueException If can't open a directory
     * @return array
     */
    public function getFirstLevelDirs($dir);
}

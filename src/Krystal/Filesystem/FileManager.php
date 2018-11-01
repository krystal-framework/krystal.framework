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

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use DirectoryIterator;
use RuntimeException;
use UnexpectedValueException;

class FileManager implements FileManagerInterface
{
    /**
     * Returns a directory name from a path
     * 
     * @param string $path
     * @return string
     */
    public static function getBaseName($path)
    {
        return pathinfo($path, \PATHINFO_BASENAME);
    }

    /**
     * Checks whether file has extension
     * 
     * @param string $baseName
     * @param array $extensions
     * @return boolean
     */
    public static function hasExtension($baseName, array $extensions)
    {
        // Lowercase names
        $baseName = strtolower($baseName);

        $extensions = array_map(function($key){
            return strtolower($key);
        }, $extensions);

        return in_array(self::getExtension($baseName), $extensions);
    }

    /**
     * Returns a directory name from a path
     * 
     * @param string $path
     * @return string
     */
    public static function getExtension($path)
    {
        return pathinfo($path, \PATHINFO_EXTENSION);
    }

    /**
     * Returns a directory name from a path
     * 
     * @param string $path
     * @return string
     */
    public static function getDirName($path)
    {
        return pathinfo($path, \PATHINFO_DIRNAME);
    }

    /**
     * Returns a file name from a path
     * 
     * @param string $path
     * @return string
     */
    public static function getFileName($path)
    {
        return pathinfo($path, \PATHINFO_FILENAME);
    }

    /**
     * Fetches mime type from a file
     * 
     * @param string $file
     * @return string
     */
    public static function getMimeType($file)
    {
        $mimeType = new MimeTypeGuesser();
        $extension = self::getExtension($file);

        return $mimeType->getTypeByExtension($file);
    }

    /**
     * Safely removes a file
     * 
     * @param string $file
     * @throws \RuntimeException When invalid file provided
     * @return boolean
     */
    public static function rmfile($file)
    {
        if (is_file($file)) {
            return chmod($file, 0777) && unlink($file);
        } else {
            throw new RuntimeException(sprintf(
                'Invalid file path supplied "%s"', $file
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
    public static function getDirTree($dir, $self = false)
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
     * @throws \RuntimeException If invalid directory path supplied
     * @return float
     */
    public static function getDirSizeCount($dir)
    {
        if (!is_dir($dir)) {
            throw new RuntimeException(sprintf('Invalid directory path supplied "%s"', $dir));
        }

        $count = 0.00;
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

        foreach ($iterator as $file) {
            $count += $file->getSize(); 
        }

        return $count;
    }

    /**
     * Tries to create a directory
     * 
     * @param string $dir
     * @return boolean
     */
    public static function createDir($dir)
    {
        if (!file_exists($dir)) {
            return mkdir($dir, 0777, true);
        } else {
            return false;
        }
    }

    /**
     * Removes everything in a directory but leaves directory itself
     * 
     * @param string $dir
     * @return boolean Depending on success
     */
    public static function cleanDir($dir)
    {
        return self::rmdir($dir) && self::createDir($dir);
    }

    /**
     * Recursively applies chmod to given directory
     * 
     * @param string $file
     * @param integer $mode
     * @param array $ignored Items that unreadable or accessible
     * @throws \UnexpectedValueException if $file is neither a directory and a file
     * @return boolean Depending on success
     */
    public static function chmod($file, $mode, array &$ignored = array())
    {
        if (is_file($file)) {
            if (!chmod($file, $mode)) {
                array_push($ignored, $file);
                return false;
            }

        } else if (is_dir($file)) {
            $items = self::getDirTree($file, true);

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
     * Removes a directory (recursively)
     * 
     * @param string $dir
     * @throws \RuntimeException if $dir isn't a path to directory
     * @return boolean Depending on success
     */
    public static function rmdir($dir)
    {
        if (!is_dir($dir)) {
            throw new RuntimeException(sprintf('Invalid directory path supplied "%s"', $dir));
        }

        $files = array_diff(scandir($dir), array('.', '..'));

        foreach ($files as $file) {
            $path = sprintf('%s/%s', $dir, $file);

            if (is_dir($path)) {
                self::rmdir($path);
            } else {
                chmod($path, 0777);
                unlink($path);
            }
        }

        return rmdir($dir);
    }

    /**
     * Copies a directory to another directory
     * 
     * @param string $file The path to the file
     * @param string $dir The dir file will be copied in
     * @throws \RuntimeException if $src isn't a path to directory
     * @return boolean Depending on success
     */
    public static function copy($src, $dst)
    {
        if (!is_dir($src)) {
            throw new RuntimeException(sprintf('Invalid directory path supplied "%s"', $src));
        }

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
     * @param string $file Target directory
     * @param string $to Target destination path
     * @return boolean
     */
    public static function move($from, $to)
    {
        return self::copy($from, $to) && self::delete($from);
    }

    /**
     * Checks whether file is empty
     * 
     * @param string $file
     * @throws \RuntimeException if invalid file path supplied
     * @return boolean
     */
    public static function isFileEmpty($file)
    {
        if (!is_file($file)) {
            throw new RuntimeException(sprintf('Invalid file path supplied'));
        }

        return mb_strlen(file_get_contents($file, 2), 'UTF-8') > 0 ? false : true;
    }

    /**
     * Returns nested directories inside provided one
     * 
     * @param string $dir
     * @throws \UnexpectedValueException If can't open a directory
     * @return array
     */
    public static function getFirstLevelDirs($dir)
    {
        $iterator = new DirectoryIterator($dir);
        $result = array();

        foreach ($iterator as $item) {
            if (!$item->isDot() && $item->isDir()) {
                array_push($result, $item->getFileName());
            }
        }

        return $result;
    }
}

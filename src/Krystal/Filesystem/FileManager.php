<?php

/**
 * This file is part of the Krystal Framework
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
     * Replaces file extension
     * 
     * @param string $path Current path
     * @param string $new New extension
     * @return string
     */
    public static function replaceExtension($path, $new)
    {
        $info = pathinfo($path);
        return ($info['dirname'] ? $info['dirname'] . DIRECTORY_SEPARATOR : '') . $info['filename'] . '.' . $new;        
    }

    /**
     * Returns basename of a path
     * 
     * @param string $path
     * @return string
     */
    public static function getBaseName($path)
    {
        return pathinfo($path, \PATHINFO_BASENAME);
    }

    /**
     * Returns extension from a path
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
     * Returns filesize in human-readable format
     * 
     * @param string $filename
     * @return string
     */
    public static function filesize($filename)
    {
        return self::humanSize(filesize($filename));
    }

    /**
     * Turns raw bytes into human-readable format
     * 
     * @param int $bytes
     * @return string
     */
    public static function humanSize($bytes)
    {
        // Make sure we can't divide by zero
        if ($bytes == 0) {
            return '0 B';
        }

        $value = floor(log($bytes, 1024));

        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $unit = $units[$value]; // Chosen unit

        return sprintf('%.02F %s', $bytes / pow(1024, $value), $unit);
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
     * Fetches mime type from a file
     * 
     * @param string $file
     * @return string
     */
    public static function getMimeType($file)
    {
        $mimeType = new MimeTypeGuesser();
        $extension = self::getExtension($file);

        return $mimeType->getTypeByExtension($extension);
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
            return unlink($file);
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
            throw new RuntimeException("Invalid directory path: $dir");
        }

        $tree = $self ? [$dir] : [];

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS), 
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isLink()) {
                continue; // skip symlinks
            }

            $tree[] = (string) $file;
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
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isLink()) {
                continue; // skip symlinks
            }

            if ($file->isFile()) {
                $count += $file->getSize();
            }
        }

        return $count;
    }

    /**
     * Tries to create a directory if one doesn't exist
     * 
     * @param string $dir Path to a directory
     * @return boolean
     */
    public static function createDir($dir)
    {
        if (!is_dir($dir)) {
            return mkdir($dir, 0755, true);
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
        if (!is_dir($dir)) {
            throw new RuntimeException(sprintf('Invalid directory path supplied "%s"', $dir));
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $item) {
            if ($item->isLink()) {
                continue; // skip symlinks
            }

            $path = $item->getPathname();

            if ($item->isDir()) {
                self::rmdir($path);
            } else {
                unlink($path);
            }
        }

        return true;
    }

    /**
     * Recursively applies chmod to given directory
     * 
     * @param string $path
     * @param integer $mode
     * @param array $ignored Items that unreadable or accessible
     * @throws \UnexpectedValueException if $path is neither a directory and a file
     * @return boolean Depending on success
     */
    public static function chmod($path, $mode, array &$ignored = array())
    {
        if (is_file($path)) {
            return chmod($path, $mode) ?: (bool) array_push($ignored, $path);
        }

        if (is_dir($path)) {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($iterator as $item) {
                if ($item->isLink()) {
                    continue; // skip symlinks
                }

                // Use getRealPath() to avoid issues with relative paths
                if (!chmod($item->getRealPath(), $mode)) {
                    $ignored[] = $item->getRealPath();
                }
            }

            // Finally, chmod the parent directory itself
            chmod($path, $mode);
            return empty($ignored);
        }

        throw new UnexpectedValueException(sprintf(
            '%s expects a path to be a directory or a file', __METHOD__
        ));
    }

    /**
     * Deletes a file or a directory
     * 
     * @param string $path
     * @return boolean
     */
    public static function delete($path)
    {
        if (is_dir($path)) {
            return self::rmdir($path);
        }

        if (is_file($path)) {
            return self::rmfile($path);
        }

        return false;
    }

    /**
     * Removes a directory recursively using memory-efficient Iterators
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

        $items = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($items as $item) {
            if ($item->isLink()) {
                continue; // skip symlinks
            }

            // Check if it's a directory or a file/link
            if ($item->isDir()) {
                rmdir($item->getPathname());
            } else {
                unlink($item->getPathname());
            }
        }

        return rmdir($dir);
    }

    /**
     * Copies a directory to another directory
     * 
     * @param string $src The path to the current directory
     * @param string $dir The dir file will be copied in
     * @param int $mode Default permissions for new directories
     * @throws \RuntimeException if $src isn't a path to directory
     * @return boolean Depending on success
     */
    public static function copy($src, $dst, $mode = 0755)
    {
        if (!is_dir($src)) {
                throw new RuntimeException(sprintf('Source is not a directory: "%s"', $src));
        }

        if (!is_dir($dst)) {
            if (!mkdir($dst, $mode, true)) {
                throw new RuntimeException(sprintf('Failed to create destination directory: "%s"', $dst));
            }
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($src, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            if ($item->isLink()) {
                continue; // skip symlinks
            }

            $target = $dst . DIRECTORY_SEPARATOR . $iterator->getSubPathName();

            if ($item->isDir()) {
                if (!is_dir($target) && !mkdir($target, $mode, true)) {
                    throw new RuntimeException(sprintf('Failed to create internal directory: "%s"', $target));
                }
            } else {
                if (!copy($item->getRealPath(), $target)) {
                    throw new RuntimeException(sprintf('Failed to copy file: "%s"', $item->getRealPath()));
                }

                // Optional: Match the file permissions of the source file
                @chmod($target, fileperms($item->getRealPath()));
            }
        }

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
        if (!file_exists($from)) {
            return false;
        }

        // Ensure the destination directory exists
        $parentDir = dirname($to);

        if (!is_dir($parentDir)) {
            self::createDir($parentDir);
        }

        // rename() is much faster as it doesn't copy data bits
        return rename($from, $to);
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
            throw new RuntimeException(sprintf('Invalid file path supplied: "%s"', $file));
        }

        // filesize() is cached by PHP and very fast
        return filesize($file) === 0;
    }

    /**
     * Returns nested directories inside provided one
     * 
     * @param string $dir
     * @throws \Exception If can't open a directory
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

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Image\Tool;

use Krystal\Filesystem\FileManager;
use LogicException;

final class FileHandler implements FileHandlerInterface
{
    /**
     * File manager
     * 
     * @var \Krystal\Filesystem\FileManager
     */
    private $fileManager;

    /**
     * Target directory
     * 
     * @var string
     */
    private $dir;

    /**
     * State initialization
     * 
     * @param string $dir Target directory
     * @param \Krystal\Filesystem\FileManager $fileManager
     * @return void
     */
    public function __construct($dir, FileManager $fileManager)
    {
        $this->dir = $dir;
        $this->fileManager = $fileManager;
    }

    /**
     * Delete all images associated with target id
     * When no $image provided, then it will removed all images inside $id folder
     * 
     * @param string $id Target id
     * @param string $image Optional filter. Image's basename to be removed for all dimensions
     * @return boolean
     */
    public function delete($id, $image = null)
    {
        // This check is extremely important!!
        // Otherwise it might remove a directory recursively
        if (!is_numeric($id)) {
            throw new LogicException('Warning: Invalid Id provided. This might remove a whole target directory recursively!');
        }

        $path = sprintf('%s/%s', $this->dir, $id);

        if ($image !== null) {
            $tree = $this->fileManager->getDirTree($path);

            foreach ($tree as $file) {
                if ($this->fileManager->getBaseName($file) == $image && is_file($file)) {
                    $this->fileManager->rmfile($file);
                }
            }

        } else {
            // Target directory might not exist, so we'd better check it
            if (is_dir($path)) {
                // Remove id directory with its content recursively
                $this->fileManager->rmdir($path);
            }
        }

        return true;
    }
}

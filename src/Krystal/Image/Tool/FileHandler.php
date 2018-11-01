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
use RuntimeException;

final class FileHandler implements FileHandlerInterface
{
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
     * @return void
     */
    public function __construct($dir)
    {
        $this->dir = $dir;
    }

    /**
     * Delete many image directories by their associated IDs at once
     * 
     * @param array $ids
     * @return boolean
     */
    public function deleteMany(array $ids)
    {
        foreach ($ids as $id) {
            $this->delete($id);
        }

        return true;
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
            throw new LogicException(sprintf('Warning: Invalid Id "%s" provided. This might remove a whole target directory recursively!', $id));
        }

        $path = sprintf('%s/%s', $this->dir, $id);

        if ($image !== null) {
            try {
                $tree = FileManager::getDirTree($path);

                foreach ($tree as $file) {
                    if (FileManager::getBaseName($file) == $image && is_file($file)) {
                        FileManager::rmfile($file);
                    }
                }
                
            } catch(RuntimeException $e){
                return false;
            }

        } else {
            // Target directory might not exist, so we'd better check it
            if (is_dir($path)) {
                // Remove id directory with its content recursively
                FileManager::rmdir($path);
            }
        }

        return true;
    }
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Image;

use Krystal\Filesystem\FileManager;
use Krystal\Image\Processor\GD\ImageFile;

final class Compressor
{
    /* Commonly used values */
    const QUALITY_WEB = 60;
    const QUALITY_MEDIUM = 70;

    /**
     * Compress all images found in a directory recursively
     * 
     * @param string $dir Path to target directory
     * @param int $quality Quality level. Defaults to web
     * @return int Total number of compressed files
     */
    public static function compress($dir, $quality = self::QUALITY_WEB)
    {
        // Create tree first
        $files = FileManager::getDirTree($dir);

        // Supported image extensions 
        $extensions = array(
            'jpg',
            'jpeg',
            'gif',
            'png'
        );

        // Default counter
        $counter = 0;

        foreach ($files as $file) {
            // In order not to waste much memory, we're gonna first check if its image file by its extension
            if (FileManager::hasExtension($file, $extensions)) {
                $image = new ImageFile($file);
                $image->save($file, $quality);

                $counter++;
            }
        }

        return $counter;
    }
}

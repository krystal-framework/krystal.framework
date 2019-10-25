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

final class FileType
{
    /**
     * Checks whether target file is Word document
     * 
     * @param string $src
     * @return boolean
     */
    public function isWord($src)
    {
        return FileManager::hasExtension($src, array(
            'doc',
            'docx'
        ));
    }

    /**
     * Checks whether target file is PDF document
     * 
     * @param string $src
     * @return boolean
     */
    public function isPdf($src)
    {
        return FileManager::hasExtension($src, array(
            'pdf'
        ));
    }

    /**
     * Checks whether target file is an image
     * 
     * @param string $src
     * @return boolean
     */
    public static function isImage($src)
    {
        return FileManager::hasExtension($src, array(
            'gif',
            'jpg',
            'jpeg',
            'png',
            'bmp'
        ));
    }
}

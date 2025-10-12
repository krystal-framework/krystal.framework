<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Image\Tool;

use Krystal\Image\Processor\Imagick\ImagickProcessor;
use Krystal\Image\Processor\GD\ImageProcessor;
use Krystal\Filesystem\FileManager;

final class Optimizer
{
    /**
     * Is this an image file? Do we even support optimization?
     * 
     * @param string $file
     * @return boolean
     */
    public static function isSupported($file)
    {
        return FileManager::hasExtension($file, [
            'jpg',
            'jpeg',
            'png',
        ]);
    }

    /**
     * Optimize image. It first, tries to convert an image to AVIF
     * If AVIF is not available, then it tries to convert it to WebP
     * If none of these options available, it does nothing.
     * 
     * @param string $file Path to target file
     * @param int $quality
     * @param boolean $unlink Whether to remove old file
     * @return array
     */
    public static function optimize($file, $quality = 50, $unlink = true)
    {
        // Stop immediatelly, if not supported
        if (!self::isSupported($file)) {
            return false;
        }

        // Check if AVIF available, first
        if (class_exists('Imagick')) {
            $output = FileManager::replaceExtension($file, 'avif');

            $processor = new ImagickProcessor($file);
            $processor->toAvif($quality)
                      ->save($output);

            if ($unlink === true) {
                FileManager::rmfile($file);
            }

            return [
                'output' => $output,
                'success' => true,
                'extension' => 'avif'
            ];
        }

        // Now check, the WebP support
        if (function_exists('imagewebp')) {
            $output = FileManager::replaceExtension($file, 'webp');

            $processor = new ImageProcessor($file);
            $processor->save($output, $quality, \IMAGETYPE_WEBP);

            if ($unlink === true) {
                FileManager::rmfile($file);
            }

            return [
                'output' => $output,
                'success' => true,
                'extension' => 'webp'
            ];
        }

        // None of these worked. Return current value
        return false;
    }
}

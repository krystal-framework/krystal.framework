<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Image\Processor\Imagick;

use Imagick;
use RuntimeException;

if (!class_exists('Imagick')) {
    throw new RuntimeException('Imagick library is not installed in your enviroment');
}

final class ImagickProcessor
{
    /**
     * Imagick instance
     * 
     * @var \Imagick
     */
    protected $image;

    /**
     * State initialization
     * 
     * @param string $file
     * @return void
     */
    public function __construct($file)
    {
        $this->image = new Imagick();

        // Try to read image
        if (!$this->image->readImage($file)) {
            // Fatal error
            throw new RuntimeException(sprintf('Can not load image file from "%s"', $file));
        }
    }

    /**
     * Convert image to AVIF format
     * 
     * @param int $quality
     * @return \Krystal\Image\Processor\ImagickProcessor
     */
    public function toAvif($quality = 80)
    {
        $this->image->setImageFormat('avif');

        // Critical Safari-compatible settings
        $this->image->setOption('avif:speed', '6');         // Faster = more compatible
        $this->image->setOption('avif:chroma', '4:2:0');    // 4:2:0 subsampling
        $this->image->setImageColorspace(Imagick::COLORSPACE_SRGB);
        $this->image->profileImage('icc', NULL);             // Strip ICC profiles
        $this->image->setImageDepth(8);                     // 8-bit depth
        $this->image->setImageAlphaChannel(Imagick::ALPHACHANNEL_OFF); // Disable alpha

        // Explicit AV1 compression if supported
        if (defined('Imagick::COMPRESSION_AV1')) {
            $this->image->setImageCompression(Imagick::COMPRESSION_AV1);
        }

        // Clamp quality for Safari
        $quality = max(50, min(90, $quality));
        $this->image->setCompressionQuality($quality);

        return $this;
    }

    /**
     * Saves image into a stream
     * 
     * @param string $output
     * @return boolean
     */
    public function save($output)
    {
        $status = $this->image->writeImage($output);
        $this->image->destroy();

        return $status;
    }
}

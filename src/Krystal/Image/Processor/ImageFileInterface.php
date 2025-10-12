<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Image\Processor;

interface ImageFileInterface
{
    /**
     * Returns current mime type
     * 
     * @return string
     */
    public function getMime();

    /**
     * Returns current image MIME-type
     * 
     * @return string
     */
    public function getType();

    /**
     * Returns current image's width
     * 
     * @return float|integer
     */
    public function getWidth();

    /**
     * Returns current image's height
     * 
     * @return float|integer
     */
    public function getHeight();

    /**
     * Returns image's resource
     * 
     * @return resource
     */
    public function &getImage();

    /**
     * Cleans taken memory
     * This should be called when work is done
     * 
     * @return void
     */
    public function done();

    /**
     * Compress and save current image file
     * 
     * @param integer $quality Image quality Medium quality by default
     * @return boolean
     */
    public function compress($quality = 75);

    /**
     * Saves an image to a file
     * 
     * @param string $path Full absolute path on the file system to save the image
     * @param integer $quality Image quality Medium quality by default
     * @param string $format Can be optionally saved in another format
     * @return boolean Depending on success
     */
    public function save($path, $quality = 75, $type = null);

    /**
     * Reloads the image
     * 
     * @return boolean
     */
    public function reload();

    /**
     * Renders the image in a browser directly
     * 
     * @param integer $quality Image quality
     * @return void
     */
    public function render($quality = 75);
}

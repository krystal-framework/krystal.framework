<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Image\Tool\Upload\Plugin;

use Krystal\Http\FileTransfer\FileUploader;
use Krystal\Http\FileTransfer\UploaderAwareInterface;
use Krystal\Image\Processor\GD\ImageProcessor;

final class OriginalSize implements UploaderAwareInterface
{
    /**
     * Target directory
     * 
     * @var string
     */
    private $dir;

    /**
     * Prefix for a directory
     * 
     * @var string
     */
    private $prefix;

    /**
     * Image quality
     * 
     * @var integer
     */
    private $quality;

    /**
     * Maximal allowed width
     * 
     * @var integer
     */
    private $maxWidth;

    /**
     * Maximal allowed height
     * 
     * @var integer
     */
    private $maxHeight;

    /**
     * State initialization
     * 
     * @param string $dir Target directory
     * @param string $prefix For a directory
     * @param integer $quality
     * @param integer $maxWidth Optionally maximal width cab be limited. 0 means don't take it into account
     * @param integer $maxHeight Optionally maximal height cab be limited. 0 means don't take it into account
     * @return void
     */
    public function __construct($dir, $prefix, $quality, $maxWidth = 0, $maxHeight = 0)
    {
        $this->dir = $dir;
        $this->prefix = $prefix;
        $this->quality = $quality;
        $this->maxWidth = $maxWidth;
        $this->maxHeight = $maxHeight;
    }

    /**
     * Returns target destination
     * 
     * @param string $id Current id
     * @return string
     */
    private function getDestination($id)
    {
        return sprintf('%s/%s/%s', $this->dir, $id, $this->prefix);
    }

    /**
     * Upload files
     * 
     * @param string $id Target id we're working with
     * @param array $files Array of file bags
     * @return boolean
     */
    public function upload($id, array $files)
    {
        $destination = $this->getDestination($id);

        if ($this->maxWidth != 0 && $this->maxHeight != 0) {
            foreach ($files as $file) {

                $imageProcessor = new ImageProcessor($file->getTmpName());
                $imageProcessor->thumb($this->maxWidth, $this->maxHeight);

                $to = sprintf('%s/%s', $destination, $file->getUniqueName());

                // Ensure that destination actually exists
                if (!is_dir($destination)) {
                    mkdir($destination, 0777, true);
                }

                // This might fail sometimes
                $imageProcessor->save($to, $this->quality);
            }

        } else {

            // Otherwise, treat image as a regular file
            $uploader = new FileUploader();

            // And just upload it
            return $uploader->upload($destination, $files);
        }

        return true;
    }
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\FileTransfer;

use Krystal\Filesystem\FileManager;
use RuntimeException;

trait FileUploadTrait
{
    /**
     * Removes file data attribute
     * 
     * @param string $attribute Relative path
     * @return boolean Depending on success
     */
    final protected function removeFileDataIfExists($attribute)
    {
        if (!empty($attribute)) {
            // Current path, we're going to check for existence
            $checkingPath = realpath(ltrim($attribute, '/'));

            // If not false, the it does exist
            if ($checkingPath !== false) {
                // Try to remove. Do nothing on failure
                try {
                    return FileManager::rmfile($checkingPath);
                } catch (RuntimeException $e) {
                    return false;
                }
            }
        }

        return false;
    }

    /**
     * Uploads file from dataFile input
     * 
     * @param string $destination Path to destination. If path doesn't exist, it won't work!
     * @param \Krystal\Http\FileTransfer\FileEntityInterface $file
     * @param string $attribute Attribute name
     * @return boolean|string Depending on success
     */
    final protected function uploadFileData($destination, FileEntityInterface $file, $attribute)
    {
        // If background image provided
        if ($file instanceof FileEntityInterface) {
            // Remove previous one, if exists
            $this->removeFileDataIfExists($attribute);

            // Absolute path to working directory
            $dirPath = realpath($destination);
            $uploader = new FileUploader();

            if ($uploader->upload($dirPath, array($file))) {
                // Final path to be returned
                $final = '/' . $destination. '/' . $file->getUniqueName();

                return $final;
            }
        }

        // By default
        return false;
    }
}

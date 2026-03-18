<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\FileTransfer;

use LogicException;
use RuntimeException;

final class FileUploader implements FileUploaderInterface
{
    /**
     * Whether to override on collision
     * 
     * @var boolean
     */
    private $override;

    /**
     * Whether a directory should be created when doesn't exist
     * 
     * @var boolean
     */
    private $destinationAutoCreate;

    /**
     * List of successfully uploaded files
     * 
     * @var array
     */
    private $uploaded = [];
    
    /**
     * List of failed uploads
     * 
     * @var array
     */
    private $failed = [];

    /**
     * State initialization
     * 
     * @param boolean $override Whether to override on collision
     * @param boolean $destinationAutoCreate
     * @return void
     */
    public function __construct($override = true, $destinationAutoCreate = true)
    {
        $this->override = (bool) $override;
        $this->destinationAutoCreate = (bool) $destinationAutoCreate;
    }

    /**
     * Returns a list of files that failed to upload/move
     * 
     * @return array
     */
    public function getFailedFiles()
    {
        return $this->failed;
    }

    /**
     * Returns a list of successfully uploaded files
     * 
     * @return array
     */
    public function getUploadedFiles()
    {
        return $this->uploaded;
    }

    /**
     * Upload files from the input
     * 
     * @param string $destination
     * @param array $files
     * @throws \LogicException if at least one value in $files is not an instance of \Krystal\Http\FileTransfer\FileEntity
     * @return boolean
     */
    public function upload($destination, array $files)
    {
        foreach ($files as $file) {
            if (!($file instanceof FileEntity)) {
                // This should never occur, but it's always better not to rely on framework users
                throw new LogicException(sprintf(
                    'Each file entity must be an instance of \Krystal\Http\FileTransfer\FileEntity, but received "%s"', gettype($file)
                ));
            }

            // Gotta ensure again, UPLOAD_ERR_OK means there are no errors
            if ($file->getError() == \UPLOAD_ERR_OK) {
                // Start trying to move a file
                if (!$this->moveSingleFile($destination, $file->getTmpName(), $file->getUniqueName())) {
                    return false;
                }
            } else {
                // Invalid file, so that cannot be uploaded. That actually should be reported before inside validator
                return false;
            }
        }

        return true;
    }

    /**
     * Moves a single file
     * 
     * @param string $destination
     * @param string $tmp
     * @param string $filename
     * @return boolean Depending on success
     */
    private function moveSingleFile($destination, $tmp, $filename)
    {
        $destination = rtrim($destination, '/\\'); // Normalize path

        if (!is_dir($destination)) {
            if ($this->destinationAutoCreate) {
                if (!mkdir($destination, 0755, true) && !is_dir($destination)) {
                    throw new RuntimeException(
                        "Failed to create directory: $destination"
                    );
                }
            } else {
                throw new RuntimeException("Destination directory does not exist: $destination");
            }
        }

        $target = sprintf('%s/%s', $destination, $filename);

        // If Remote file exists and we don't want to override it, so let's stop here
        if (is_file($target) && !$this->override) {
            throw new RuntimeException("File already exists and overriding is disabled: $target");
        }

        if (move_uploaded_file($tmp, $target)) {
            $this->uploaded[] = $target;
            return true;
        } else {
            $this->failed[] = $target;
            return false;
        }
    }
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\FileTransfer;

interface ChainInterface
{
    /**
     * Adds an uploader that implements UploaderAwareInterface
     * 
     * @param \Krystal\Http\FileTransfer\UploaderAwareInterface $uploader
     * @return \Krystal\Http\FileTransfer\UploadChain
     */
    public function addUploader(UploaderAwareInterface $uploader);

    /**
     * Add more uploaders
     * 
     * @param array $uploaders
     * @return Krystal\Http\FileTransfer\UploadChain
     */
    public function addUploaders(array $uploaders);

    /**
     * Uploads via all defined uploaders
     * 
     * @param string $id
     * @param array $files
     * @return void
     */
    public function upload($id, array $files);
}

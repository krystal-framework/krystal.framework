<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\FileTransfer;

interface UploaderAwareInterface
{
    /**
     * Upload all files we have from the input
     * 
     * @param string $id
     * @param array $files
     * @return boolean
     */
    public function upload($id, array $files);
}

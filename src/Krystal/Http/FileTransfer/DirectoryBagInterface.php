<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\FileTransfer;

interface DirectoryBagInterface
{
    /**
     * Returns a path to a file in the target directory
     * 
     * @param string $id Nested directory's id
     * @param string $file A filename inside that nested directory
     * @return string
     */
    public function getPath($id, $file = null);

    /**
     * Uploads a file into a directory by its id
     * 
     * @param string $id Nested directory's id
     * @param array $files An array of file bags
     * @return boolean
     */
    public function upload($id, array $files);

    /**
     * Removes a directory by its nested id
     * Or removes a file insider that nested directory by its nested filename
     * 
     * @param string $id Nested directory's id
     * @param string $filename Filename to be removed. Optional
     * @return boolean
     */
    public function remove($id, $filename = null);
}

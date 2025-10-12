<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Image\Tool;

/**
 * API for ImageManager
 */
interface ImageManagerInterface
{
    /**
     * Returns prepared ImageBag instance
     * 
     * @return ImageBag
     */
    public function getImageBag();

    /**
     * Uploads an image from $files
     * 
     * @param string $id
     * @param array/FileEntity $files Files collection
     * @return boolean
     */
    public function upload($id, $files);

    /**
     * Delete directories by their associated IDs
     * 
     * @param array $ids
     * @return boolean
     */
    public function deleteMany(array $ids);

    /**
     * Deletes a directory by its id
     * 
     * @param string $id
     * @param string $image Optional image filter
     * @return boolean
     */
    public function delete($id, $image = null);
}

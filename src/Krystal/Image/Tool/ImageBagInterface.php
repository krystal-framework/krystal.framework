<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Image\Tool;

interface ImageBagInterface
{
    /**
     * Defines a target id
     * 
     * @param string $id
     * @return void
     */
    public function setId($id);

    /**
     * Defines a basename of a cover
     * 
     * @param string $cover
     * @return void
     */
    public function setCover($cover);

    /**
     * Returns image path on the file-system filtered by provided size
     * 
     * @param string $size
     * @throws RuntimeException when not ready to be used
     * @return string
     */
    public function getPath($size);

    /**
     * Returns image URL filtered by provided size
     * 
     * @param string $size
     * @throws RuntimeException when not ready to be used
     * @return string
     */
    public function getUrl($size);
}

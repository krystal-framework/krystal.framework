<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Image\Tool;

interface LocationBuilderInterface
{
    /**
     * Build a path to the image on the filesystem
     * 
     * @param string $id
     * @param string $image
     * @param string $dimension
     * @return string
     */
    public function buildPath($id, $image, $dimension);

    /**
     * Builds an URL to the image
     * 
     * @param string $id
     * @param string $image
     * @param string $dimension
     * @return string
     */
    public function buildUrl($id, $image, $dimension);
}

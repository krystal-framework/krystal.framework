<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Image\Tool\Upload\Plugin;

final class OriginalSizeFactory
{
    /**
     * Builds original size uploader
     * 
     * @param integer $quality Desired quality
     * @param array $options Options
     * @return \Krystal\Image\Tool\Upload\Plugin\OriginalSize
     */
    public function build($dir, $quality, array $options = array())
    {
        // Also, it would make sense to value user-provided prefix against regular [A-Z0-9] pattern
        if (!isset($options['prefix'])) {
            $options['prefix'] = 'original';
        }

        // By default, we don't want to limit dimensions
        $maxWidth = 0;
        $maxHeight = 0;

        // If we have maximal dimensions limit, in configuration
        if (isset($options['max_width']) && isset($options['max_height'])) {

            $maxWidth = $options['max_width'];
            $maxHeight = $options['max_height'];
        }

        return new OriginalSize($dir, $options['prefix'], $quality, $maxWidth, $maxHeight);
    }
}

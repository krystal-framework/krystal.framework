<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Profiler;

class Memory
{
    /**
     * Returns memory usage
     * 
     * @return string
     */
    public static function getUsage()
    {
        $size = memory_get_usage(true);
        $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');

        // Count the exponent 
        $exp = floor(log($size, 1024));
        $pow = pow(1024, $exp);

        return round($size / $pow, 2).' '.strtoupper($unit[$exp]);
    }
}

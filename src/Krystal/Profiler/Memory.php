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

use Krystal\Filesystem\FileManager;

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
        return FileManager::humanSize($size);
    }
}

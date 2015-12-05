<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\FileTransfer\Filter;

interface FileInputFilerInterface
{
    /**
     * Filter names inside each file entity applying defined filter
     * 
     * @param array $files
     * @return void
     */
    public function filter(array $files);
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\FileTransfer;

interface UrlPathGeneratorInterface
{
    /**
     * Returns full URL path to a file
     * 
     * @param string $id Nested directory's id
     * @param string $filename Required filename
     * @return string
     */
    public function getPath($id, $filename);
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Config\File;

interface FileTypeInterface
{
    /**
     * Each descendant must represent its nature
     * 
     * @param array $config
     * @return string
     */
    public function render(array $config);

    /**
     * Fetches as PHP array representation
     * 
     * @param string $file
     * @return string 
     */
    public function fetch($file);
}

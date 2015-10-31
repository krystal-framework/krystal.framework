<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\View;

interface AssetPathInterface
{
    /**
     * Replaces a module path inside provided path
     * 
     * @param string $path Target path
     * @return string
     */
    public function replace($path);
}

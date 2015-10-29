<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Stdlib;

interface MagicQuotesFilterInterface
{
    /**
     * Deactivates magic quotes at runtime
     * 
     * @return void
     */
    public function deactivate();

    /**
     * Checks whether magic quotes are enabled
     * 
     * @return boolean
     */
    public function enabled();

    /**
     * Recursively filter slashes in array
     * 
     * @param mixed $value
     * @return array
     */
    public function filter($value);
}

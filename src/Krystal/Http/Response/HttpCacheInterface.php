<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\Response;

interface HttpCacheInterface
{
    /**
     * Starts to capture
     * 
     * @param integer $timestamp Last modified timestamp
     * @param integer $ttl Time to live in seconds
     * @return void
     */
    public function configure($timestamp, $ttl);
}

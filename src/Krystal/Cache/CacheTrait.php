<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Cache;

use Closure;

trait CacheTrait
{
    /**
     * Returns value from a cache by key.
     * Only calls the callback when the cache key is missing
     * 
     * @param string $key
     * @param \Closure Callback function that returns if key is missing
     * @param integer $ttl Time to live in seconds
     * @return mixed
     */
    public function getOnce($key, Closure $callback, $ttl)
    {
        if ($this->has($key)) {
            return $this->get($key);
        } else {
            $value = $callback();
            $this->set($key, $value, $ttl);
            return $value;
        }
    }
}

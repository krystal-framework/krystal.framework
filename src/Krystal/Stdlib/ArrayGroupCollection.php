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

class ArrayGroupCollection extends ArrayCollection
{
    /**
     * Checks whether key in collection exist
     * 
     * @param string $target
     * @return boolean
     */
    public function hasKey($target)
    {
        foreach ($this->collection as $group => $hashMap) {
            if (array_key_exists($target, $hashMap)) {
                return true;
            }
        }

        // By default
        return false;
    }

    /**
     * Find a value by its associated key
     * 
     * @param string $key
     * @param mixed $default Default value to be returned
     * @return mixed
     */
    public function findByKey($target, $default = '')
    {
        foreach ($this->collection as $group => $hashMap) {
            if (array_key_exists($target, $hashMap)) {
                return $hashMap[$target];
            }
        }

        return $default;
    }
}

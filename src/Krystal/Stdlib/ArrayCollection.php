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

class ArrayCollection
{
    /**
     * Current collection
     * 
     * @var array
     */
    protected $collection;

    /**
     * Checks whether key in collection exist
     * 
     * @param string $key
     * @return boolean
     */
    public function hasKey($key)
    {
        return array_key_exists($key, $this->collection);
    }

    /**
     * Find a value by its associated key
     * 
     * @param string $key
     * @return mixed
     */
    public function findByKey($key)
    {
        if ($this->hasKey($key)) {
            return $this->collection[$key];
        } else {
            return '';
        }
    }

    /**
     * Returns the collection
     * 
     * @return array
     */
    public function getAll()
    {
        return $this->collection;
    }
}

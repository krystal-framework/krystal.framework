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
     * Returns internal collection
     * 
     * @return array
     */
    protected function getCollection()
    {
        if (ArrayUtils::hasAtLeastOneArrayValue($this->collection)) {
            $output = [];

            foreach ($this->collection as $key => $array) {
                $output = $output + $array;
            }

            return $output;
        } else {
            return $this->collection;
        }
    }

    /**
     * Check whether many keys exist
     * 
     * @param array $keys
     * @return boolean
     */
    public function hasKeys(array $keys)
    {
        foreach ($keys as $key) {
            if (!$this->hasKey($key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks whether key in collection exist
     * 
     * @param string $key
     * @return boolean
     */
    public function hasKey($key)
    {
        return array_key_exists($key, $this->getCollection());
    }

    /**
     * Find a value by its associated key
     * 
     * @param string $key
     * @param mixed $default Default value to be returned
     * @return mixed
     */
    public function findByKey($key, $default = '')
    {
        if ($this->hasKey($key)) {
            $collection = $this->getCollection();
            return $collection[$key];
        } else {
            return $default;
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

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Filter;

use ArrayAccess;

/**
 * Input decorator would help to prevent notices when accessing undefined array keys
 * returning an empty string instead
 */
final class InputDecorator implements ArrayAccess
{
    /**
     * Target data to be decorated
     * 
     * @var array
     */
    private $data = array();

    /**
     * State initialization
     * 
     * @param array $data
     * @return void
     */
    public function __construct(array $data = array())
    {
        $this->data = $data;
    }

    /**
     * Checks whether a key exist in the target array
     * 
     * @param string $key Target key to be checked for existence
     * @return boolean
     */
    private function has($key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * Appends data
     * 
     * @param string $offset
     * @param string $value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    /**
     * Checks whether a key exists in the target array
     * 
     * @param string $key
     * @return boolean
     */
    public function offsetExists($key)
    {
        return $this->has($key);
    }

    /**
     * Removes a value from the target array
     * 
     * @param string $key
     * @return void
     */
    public function offsetUnset($key)
    {
        if ($this->has($key)) {
            unset($this->data[$key]);
        }
    }

    /**
     * Returns a value from the target array
     * 
     * @param string $key
     * @return string
     */
    public function offsetGet($key)
    {
        $default = '';

        if ($this->has($key)) {
            return $this->data[$key];
        } else {
            return $default;
        }
    }
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Filter;

use ArrayAccess;
use Iterator;

/**
 * Input decorator would help to prevent notices when accessing undefined array keys
 * returning an empty string instead
 */
final class InputDecorator implements ArrayAccess, Iterator
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
     * Set the internal pointer of an array to its first element
     * 
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function rewind()
    {
        reset($this->data);
    }

    /**
     * Return the current element in an array
     * 
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function current()
    {
        return current($this->data);
    }

    /**
     * Returns next pointer
     * 
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function key()
    {
        return key($this->data);
    }

    /**
     * Returns next pointer
     * 
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function next()
    {
        return next($this->data);
    }

    /**
     * Checking for existence
     * 
     * @return boolean
     */
    #[\ReturnTypeWillChange]
    public function valid()
    {
        $key = key($this->data);
        return ($key !== null && $key !== false);
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
    #[\ReturnTypeWillChange]
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
    #[\ReturnTypeWillChange]
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
    #[\ReturnTypeWillChange]
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
    #[\ReturnTypeWillChange]
    public function offsetGet($key)
    {
        $default = '';

        if ($this->has($key)) {
            return (string) $this->data[$key];
        } else {
            return $default;
        }
    }
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\FileTransfer;

use ArrayAccess;
use BadMethodCallException;

final class FileEntity implements FileEntityInterface, ArrayAccess
{
    /**
     * Entity data container
     * 
     * @var array
     */
    private $container = array();

    /**
     * Sets an offset
     * 
     * @param string $offset
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        throw new BadMethodCallException('The offset can not be set');
    }

    /**
     * Checks whether offset is defined
     * 
     * @param string $offset
     * @return boolean
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Unsets an offset
     * 
     * @param string $offset
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        throw new BadMethodCallException('The offset can not be unset');
    }

    /**
     * Returns a value of offset
     * 
     * @param string $offset
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Returns file extension
     * 
     * @return string
     */
    public function getExtension()
    {
        $extension = pathinfo($this->getName(), \PATHINFO_EXTENSION);
        return strtolower($extension);
    }

    /**
     * Returns unique name for uploaded file
     * 
     * @return string
     */
    public function getUniqueName()
    {
        $key = 'uniq';

        // Lazy initialization
        if (!isset($this->container[$key])) {
            $extension = $this->getExtension();

            // If extension avaiable, use it
            if ($extension) {
                $name = sprintf('%s.%s', uniqid(), $extension);
            } else {
                // Otherwise just filename without extension
                $name = uniqid();
            }

            $this->container[$key] = $name;
        }

        return $this->container[$key];
    }

    /**
     * Defines type for a file
     * 
     * @param string $type
     * @return \Krystal\Http\FileTransfer\FileEntity
     */
    public function setType($type)
    {
        $this->container['type'] = $type;
        return $this;
    }

    /**
     * Returns file type
     * 
     * @return string
     */
    public function getType()
    {
        return $this->container['type'];
    }

    /**
     * Defines a name
     * 
     * @param string $name
     * @return \Krystal\Http\FileTransfer\FileEntity
     */
    public function setName($name)
    {
        $this->container['name'] = $name;
        return $this;
    }

    /**
     * Returns a name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->container['name'];
    }

    /**
     * Defines a temporary name
     * 
     * @param string $tmpName
     * @return \Krystal\Http\FileTransfer\FileEntity
     */
    public function setTmpName($tmpName)
    {
        $this->container['tmp_name'] = $tmpName;
        return $this;
    }

    /**
     * Returns temporary location
     * 
     * @return string
     */
    public function getTmpName()
    {
        return $this->container['tmp_name'];
    }

    /**
     * Defines an error
     * 
     * @param string $error
     * @return \Krystal\Http\FileTransfer\FileEntity
     */
    public function setError($error)
    {
        $this->container['error'] = $error;
        return $this;
    }

    /**
     * Returns error message
     * 
     * @return string
     */
    public function getError()
    {
        return $this->container['error'];
    }

    /**
     * Defines a size
     * 
     * @param string $size
     * @return \Krystal\Http\FileTransfer\FileEntity
     */
    public function setSize($size)
    {
        $this->container['size'] = $size;
        return $this;
    }

    /**
     * Returns file size
     * 
     * @return string
     */
    public function getSize()
    {
        return $this->container['size'];
    }
}

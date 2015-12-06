<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\FileTransfer;

final class FileEntity implements FileEntityInterface
{
    /**
     * Detected MIMI-type
     * 
     * @var string
     */
    private $type;

    /**
     * Original file name
     * 
     * @var string
     */
    private $name;

    /**
     * Auto-generated path to temporary file
     * 
     * @var string
     */
    private $tmpName;

    /**
     * Error code if present
     * 
     * @var string
     */
    private $error;

    /**
     * File size in bytes
     * 
     * @var integer
     */
    private $size;

    /**
     * Returns unique name for uploaded file
     * 
     * @return string
     */
    public function getUniqueName()
    {
        $extension = strtolower(pathinfo($this->getName(), \PATHINFO_EXTENSION));
        return sprintf('%s.%s', uniqid(), $extension);
    }

    /**
     * Defines type for a file
     * 
     * @param string $type
     * @return \Krystal\Http\FileTransfer\FileEntity
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Returns file type
     * 
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Defines a name
     * 
     * @param string $name
     * @return \Krystal\Http\FileTransfer\FileEntity
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Returns a name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Defines a temporary name
     * 
     * @param string $tmpName
     * @return \Krystal\Http\FileTransfer\FileEntity
     */
    public function setTmpName($tmpName)
    {
        $this->tmpName = $tmpName;
        return $this;
    }

    /**
     * Returns temporary location
     * 
     * @return string
     */
    public function getTmpName()
    {
        return $this->tmpName;
    }

    /**
     * Defines an error
     * 
     * @param string $error
     * @return \Krystal\Http\FileTransfer\FileEntity
     */
    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    /**
     * Returns error message
     * 
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Defines a size
     * 
     * @param string $size
     * @return \Krystal\Http\FileTransfer\FileEntity
     */
    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    /**
     * Returns file size
     * 
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }
}
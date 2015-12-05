<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Text;

interface CsvLimitedToolInterface
{
    /**
     * Returns values
     * 
     * @param boolean $implode Whether result must be an array or a string separated by comma
     * @return string|array
     */
    public function getValues($implode = true);

    /**
     * Loads data from a string
     * 
     * @param string $data
     * @return void
     */
    public function load($data);

    /**
     * Checks whether value exists
     * 
     * @param string $value
     * @return boolean
     */
    public function exists($value);

    /**
     * Removes a value from the stack
     * 
     * @param string $value
     * @return void
     */
    public function remove($value);
    
    /**
     * Prepends a value to the beginning of the stack
     * 
     * @param string $value
     * @return boolean
     */
    public function prepend($value);

    /**
     * Appends a value to the beginning of the stack
     * 
     * @param string $value Target value to be appended
     * @return boolean
     */
    public function append($value);
}

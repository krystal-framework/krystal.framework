<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\ParamBag;

interface ParamBagInterface
{
    /**
     * Checks whether parameter is registered
     * 
     * @param string $param Param name to be checked for existence
     * @return boolean
     */
    public function exists($param);

    /**
     * Appends a parameter
     * 
     * @param string $key
     * @param mixed $value
     * @return \Krystal\ParamBag\ParamBag
     */
    public function set($key, $value);

    /**
     * Returns parameter's value
     * 
     * @param string $param
     * @param mixed $default Default value to be returned in case $param doesn't exist
     * @return mixed
     */
    public function get($param, $default = false);
}

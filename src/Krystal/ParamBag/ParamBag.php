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

/**
 * The service used to read static configuration from "params" section in configuration array
 */
final class ParamBag implements ParamBagInterface
{
    /**
     * All available parameters
     * 
     * @var array
     */
    private $params = array();

    /**
     * State initialization
     * 
     * @param array $defaults Default parameters
     * @return void
     */
    public function __construct(array $params = array())
    {
        $this->params = $params;
    }

    /**
     * Checks whether parameter is registered
     * 
     * @param string $param Param name to be checked for existence
     * @return boolean
     */
    public function has($param)
    {
        return array_key_exists($param, $this->params);
    }

    /**
     * Checks whether several parameters are defined at once
     * 
     * @param array $params
     * @return boolean
     */
    public function hasMany(array $params)
    {
        foreach ($params as $param) {
            if (!$this->has($param)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Appends a parameter
     * 
     * @param string $key
     * @param mixed $value
     * @return \Krystal\ParamBag\ParamBag
     */
    public function set($key, $value)
    {
        $this->params[$key] = $value;
        return $this;
    }

    /**
     * Append many parameters
     * 
     * @param array $params
     * @return \Krystal\ParamBag\ParamBag
     */
    public function setMany(array $params)
    {
        foreach ($params as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

	/**
     * Returns parameter's value
     * 
     * @param string $param
     * @param mixed $default Default value to be returned in case $param doesn't exist
     * @return mixed
     */
    public function get($param, $default = false)
    {
        if ($this->has($param)) {
            return $this->params[$param];
        } else {
            return $default;
        }
    }
}

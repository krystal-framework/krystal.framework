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

use RuntimeException;
use LogicException;

class VirtualEntity
{
    /**
     * Entity container
     * 
     * @var array
     */
    protected $container = array();

    /**
     * State initialization
     * 
     * @param boolean $once Whether writing can be done only once
     * @return void
     */
    public function __construct($once = true)
    {
        $this->once = $once;
    }

    /**
     * Checks whether bag has virtual property
     * 
     * @param string $property
     * @return boolean
     */
    public function has($property)
    {
        return array_key_exists($property, $this->container);
    }

    /**
     * Handles the Data Value object logic
     * 
     * @param string $method
     * @param array $arguments
     * @param mixed $default Default value to be returned if getter fails
     * @throws \RuntimeException If has and violated writing constraint
     * @throws \LogicException If trying to get undefined property
     * @return mixed
     */
    private function handle($method, array $arguments, $default)
    {
        // Target method we're working with
        $method = strtolower($method);

        // Target property
        $property = substr($method, 3);
        $start = substr($method, 0, 3);

        // Are we dealing with a getter?
        if ($start == 'get') {
            if ($property === false) {
                throw new LogicException('Attempted to call a getter on undefined property');
            }

            // getter is being used
            if (array_key_exists($property, $this->container)) {
                return $this->container[$property];
            } else {
                return $default;
            }
        }

        // Are we dealing with a setter?
        if ($start == 'set') {
            if ($this->once === true && isset($this->container[$property])) {
                throw new RuntimeException(sprintf('You can write to "%s" only once', $property));
            }

            // setter is being used
            $this->container[$property] = $arguments[0];
            return $this;
        }
    }

    /**
     * Method overloading : handle undefined method calls
     * 
     * @param string $method
     * @param array $arguments
     * @return mixed
     */
    public function __call($method, array $arguments)
    {
        return $this->handle($method, $arguments, null);
    }
}

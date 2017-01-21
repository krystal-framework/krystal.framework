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

use Krystal\Security\Filter;
use Krystal\Security\Sanitizeable;
use RuntimeException;
use LogicException;
use UnderflowException;

class VirtualEntity implements Sanitizeable
{
    /**
     * Entity container
     * 
     * @var array
     */
    protected $container = array();

    /**
     * Defines whether properties can be overridden or not
     * 
     * @var boolean
     */
    protected $once;

    /**
     * State initialization
     * 
     * @param boolean $once Whether writing can be done only once
     * @return void
     */
    public function __construct($once = true)
    {
        $this->once = (bool) $once;
    }

    /**
     * Returns defined properties
     * 
     * @return array
     */
    public function getProperties()
    {
        return $this->container;
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
            if ($this->has($property)) {
                return $this->container[$property];
            } else {
                return $default;
            }
        }

        // Are we dealing with a setter?
        if ($start == 'set') {
            if ($this->once === true && $this->has($property)) {
                throw new RuntimeException(sprintf('You can write to "%s" only once', $property));
            }

            // Make sure the first argument is supplied
            if (array_key_exists(0, $arguments)) {
                $value = $arguments[0];
            } else {
                throw new UnderflowException(sprintf(
                    'The virtual setter for "%s" expects at least one argument, which would be a value. None supplied.', $property
                ));
            }

            // If filter is defined, then use it
            if (isset($arguments[1])) {
                // Override value with filtered one
                $value = Filter::sanitize($value, $arguments[1]);
            }

            // setter is being used
            $this->container[$property] = $value;
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

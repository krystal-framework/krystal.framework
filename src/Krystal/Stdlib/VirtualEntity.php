<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Stdlib;

use Krystal\Security\Filter;
use Krystal\Security\Sanitizeable;
use Krystal\Text\TextUtils;
use RuntimeException;
use LogicException;
use UnderflowException;
use ArrayAccess;

class VirtualEntity implements Sanitizeable, ArrayAccess
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
     * Whether mode is strict
     * 
     * @var boolean
     */
    protected $strict;

    /**
     * State initialization
     * 
     * @param boolean $once Whether writing can be done only once
     * @param boolean $strict Whether mode is strict
     * @return void
     */
    public function __construct($once = true, $strict = false)
    {
        $this->once = (bool) $once;
        $this->strict = (bool) $strict;
    }

    /**
     * Checks whether offset exist
     * 
     * @param mixed $offset
     * @return boolean
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * Returns an offset
     * 
     * @param mixed $offset
     * @return boolean
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        if ($this->has($offset)) {
            return $this->container[$offset];
        }
    }

    /**
     * Sets an offset
     * 
     * @param mixed $offset
     * @param mixed $value
     * @return boolean
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        $this->container[$offset] = $value;
    }

    /**
     * Unsets an offset
     * 
     * @param mixed $offset
     * @return boolean
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        if ($this->has($offset)) {
            unset($this->container[$offset]);
        }
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
        return array_key_exists((string) $property, $this->container);
    }

    /**
     * Returns a property
     * 
     * @param string $property
     * @return string
     */
    public function get($property)
    {
        return $this->container[$property];
    }

    /**
     * Handles the Data Value object logic
     * 
     * @param string $method
     * @param array $arguments
     * @param mixed $default Default value to be returned if getter fails
     * @throws \RuntimeException If has and violated writing constraint or method doesn't start from get or get
     * @throws \LogicException If trying to get undefined property
     * @return mixed
     */
    private function handle($method, array $arguments, $default)
    {
        // Target property (drop set or get word)
        $property = substr($method, 3);

        // Convert to snake case
        $property = TextUtils::snakeCase($property);

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

        // Are we dealing with a setter?
        } else if ($start == 'set') {
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

        } else {
            // Throw exception only on strict mode
            if ($this->strict == true) {
                throw new RuntimeException(sprintf(
                    'Virtual method name must start either from "get" or "set". You provided "%s"', $method
                ));
            }
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

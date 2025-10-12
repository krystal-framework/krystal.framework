<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Serializer;

abstract class AbstractSerializer
{
    /**
     * Determines whether the given variable can be serialized.
     *
     * Supports serialization of arrays and objects only.
     *
     * @param mixed $var The variable to evaluate.
     * @return bool TRUE if serializable, FALSE otherwise.
     */
    public function isSerializeable($var)
    {
        return in_array(gettype($var), array('array', 'object'));
    }

    /**
     * Checks whether the given string contains valid serialized data.
     *
     * Implementations define the serialization format (e.g., JSON or PHP native).
     *
     * @param string $string The string to check.
     * @return bool TRUE if the string is valid serialized data, FALSE otherwise.
     */
    abstract public function isSerialized($string);

    /**
     * Serializes the given variable.
     *
     * @param array|object $var The variable to serialize.
     * @return string The serialized representation.
     */
    abstract public function serialize($var);

    /**
     * Restores a variable from its serialized representation.
     *
     * @param string $serialized The serialized string.
     * @return array|object The unserialized value.
     */
    abstract public function unserialize($serialized);
}

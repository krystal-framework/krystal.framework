<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Serializer;

/**
 * Handles serialization using PHP's native serialize()/unserialize() functions.
 */
final class NativeSerializer extends AbstractSerializer
{
    /**
     * {@inheritDoc}
     */
    public function serialize($var)
    {
        return serialize($var);
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($string)
    {
        return unserialize($string);
    }

    /**
     * {@inheritDoc}
     */
    public function isSerialized($string)
    {
        if (!is_string($string)) {
            return false;
        }

        return $string == serialize(false) || @unserialize($string) !== false;
    }
}

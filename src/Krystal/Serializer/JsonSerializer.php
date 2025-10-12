<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Serializer;

/**
 * Handles serialization using the JSON format.
 *
 * Provides legacy-compatible and modern (PHP 8.3+) JSON validation.
 */
final class JsonSerializer extends AbstractSerializer
{
    /**
     * {@inheritDoc}
     */
    public function serialize($var)
    {
        return json_encode($var, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * {@inheritDoc}
     */
    public function unserialize($serialized)
    {
        return json_decode($serialized, true);
    }

    /**
     * {@inheritDoc}
     */
    public function isSerialized($string)
    {
        if (!is_string($string)) {
            return false;
        }

        // Use native json_validate() if available (PHP 8.3+)
        if (function_exists('json_validate')) {
            return json_validate($string);
        }

        // Fallback for older PHP versions. @ - intentionally
        @json_decode($string);
        return (json_last_error() === \JSON_ERROR_NONE);
    }
}

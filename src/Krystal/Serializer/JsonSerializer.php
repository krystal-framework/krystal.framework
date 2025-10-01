<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Serializer;

final class JsonSerializer extends AbstractSerializer
{
    /**
     * {@inheritDoc}
     */
    public function serialize($var)
    {
        return json_encode($var, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
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
    public function isSerialized($var)
    {
        json_decode($var);
        return (json_last_error() === \JSON_ERROR_NONE);
    }
}

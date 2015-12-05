<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Input\Constraint;

use Krystal\Serializer\AbstractSerializer;

/**
 * Checks whether a string is serialized
 */
final class Serialized extends AbstractConstraint
{
    /**
     * Serialization service
     * 
     * @var \Krystal\Serializer\AbstractSerializer
     */
	private $adapter;

    /**
     * {@inheritDoc}
     */
    protected $message = 'Given string is not serialized';

    /**
     * State initialization
     * 
     * @param \Krystal\Serializer\AbstractSerializer $adapter
     * @return void
     */
    public function __construct(AbstractSerializer $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * {@inheritDoc}
     */
    public function isValid($target)
    {
        if ($this->adapter->isSerialized($target)) {
            return true;
        } else {
            $this->violate($this->message);
            return false;
        }
    }
}

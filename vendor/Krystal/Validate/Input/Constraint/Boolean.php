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

/**
 * Returns true for true-like values
 */
final class Boolean extends AbstractConstraint
{
    /**
     * {@inheritDoc}
     */
    protected $message = 'A value must represent a boolean value';

    /**
     * {@inheritDoc}
     */
    public function isValid($target)
    {
        if (filter_var($target, \FILTER_VALIDATE_BOOLEAN)) {
            return true;
        } else {
            $this->violate($this->message);
            return false;
        }
    }
}

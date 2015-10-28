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

final class Timestamp extends AbstractConstraint
{
    /**
     * {@inheritDoc}
     */
    protected $message = 'Given string does not look like a valid UNIX-timestamp';

    /**
     * {@inheritDoc}
     */
    public function isValid($target)
    {
        if (is_numeric($target) && strlen($target) < 11) {
            return true;
        } else {
            $this->violate($this->message);
            return false;
        }
    }
}

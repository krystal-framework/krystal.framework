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

final class Lowercase extends AbstractConstraint
{
    /**
     * {@inheritDoc}
     */
    protected $message = 'A value should be in lowercase';

    /**
     * {@inheritDoc}
     */ 
    public function isValid($target)
    {
        if (mb_strtolower($target, 'UTF-8') === $target) {
            return true;
        } else {
            $this->violate($this->message);
            return false;
        }
    }
}

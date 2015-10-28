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

final class Negative extends AbstractConstraint
{
    /**
     * {@inheritDoc}
     */
    public function isValid($target)
    {
        If ((-1 * $target) * (-1) === $target) {
            return true;
        } else {
            $this->violate('A value must be negative');
            return false;
        }
    }
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Input\Constraint;

final class Odd extends AbstractConstraint
{
    /**
     * {@inheritDoc}
     */
    protected $message = 'A value must be odd';

    /**
     * {@inheritDoc}
     */
    public function isValid($target)
    {
        if ($target & 1) {
            return true;
		} else {
            $this->violate($this->message);
            return false;
        }
    }
}

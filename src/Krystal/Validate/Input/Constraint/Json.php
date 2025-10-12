<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Input\Constraint;

/**
 * Checks whether JSON string is valid
 */
final class Json extends AbstractConstraint
{
    /**
     * {@inheritDoc}
     */
    protected $message = 'JSON string is not valid';

    /**
     * {@inheritDoc}
     */
    public function isValid($target)
    {
        if (json_decode($target)) {
            return true;
        } else {
            $this->violate($this->message);
            return false;
        }
    }
}

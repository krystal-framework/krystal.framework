<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Input\Constraint;

final class IpPattern extends AbstractConstraint
{
    /**
     * {@inheritDoc}
     */
    protected $message = 'A value does not match IP-pattern';

    /**
     * {@inheritDoc}
     */
    public function isValid($target)
    {
        if (filter_var($target, \FILTER_VALIDATE_IP)) {
            return true;
        } else {
            $this->violate($this->message);
            return false;
        }
    }
}

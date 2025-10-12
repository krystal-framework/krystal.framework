<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Input\Constraint;

/**
 * Checks whether given charset is supported
 */
final class Charset extends AbstractConstraint
{
    /**
     * {@inheritDoc}
     */
    protected $message = 'Unknown charset supplied';

    /**
     * {@inheritDoc}
     */
    public function isValid($target)
	{
        if (in_array($target, mb_list_encodings())) {
            return true;
        } else {
            $this->violate($this->message);
            return false;
        }
    }
}

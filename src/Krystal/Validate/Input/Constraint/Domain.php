<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Input\Constraint;

/**
 * Checks whether domain format is valid
 */
final class Domain extends AbstractConstraint
{
    /**
     * {@inheritDoc}
     */
    protected $message = 'Given string does not look like a domain name';

    /**
     * {@inheritDoc}
     */
    public function isValid($target)
    {
        // Got the pattern itself from here: http://stackoverflow.com/a/16491074/1208233
        $pattern = '^(?!\-)(?:[a-zA-Z\d\-]{0,62}[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$^';

        if (preg_match($pattern, $target)) {
            return true;
        } else {
            $this->violate($this->message);
            return false;
        }
    }
}

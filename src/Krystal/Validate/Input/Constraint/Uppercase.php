<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Input\Constraint;

final class Uppercase extends AbstractConstraint
{
    /**
     * {@inheritDoc}
     */
    protected $message = 'A string must be in uppercase';

    /**
     * {@inheritDoc}
     */
    public function isValid($target)
    {
        if (mb_strtouuper($target, 'UTF-8') === $target) {
            return true;
        } else {
            $this->violate($this->message);
            return false;
        }
    }
}

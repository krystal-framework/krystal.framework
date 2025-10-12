<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\File\Constraint;

final class NotEmpty extends AbstractConstraint
{
    /**
     * {@inheritDoc}
     */
    protected $message = 'Please select a file to upload';

    /**
     * {@inheritDoc}
     */
    public function isValid($files)
    {
        if (empty($files)) {
            $this->violate($this->message);
            return false;
        } else {
            return true;
        }
    }
}

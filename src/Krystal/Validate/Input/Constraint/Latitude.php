<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Input\Constraint;

final class Latitude extends AbstractConstraint
{
    /**
	 * {@inheiritDoc}
	 */
    protected $message = 'Invalid latitude format provided';

    /**
	 * {@inheritDoc}
	 */
    public function isValid($target)
    {
        if (preg_match('/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/', $target)) {
            return true;
		} else {
            $this->violate($this->message);
            return false;
        }
    }
}

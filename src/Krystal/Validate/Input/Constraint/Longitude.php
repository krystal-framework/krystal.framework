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

final class Longitude extends AbstractConstraint
{
    /**
	 * {@inheiritDoc}
	 */
    protected $message = 'Invalid longitude format provided';

    /**
	 * {@inheritDoc}
	 */
    public function isValid($target)
    {
        if (preg_match('/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/', $target)) {
            return true;
		} else {
            $this->violate($this->message);
            return false;
        }
    }
}

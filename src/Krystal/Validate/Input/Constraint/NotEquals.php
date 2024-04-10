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

final class NotEquals extends AbstractConstraint
{
    /**
     * {@inheritDoc}
     */
    protected $message = 'The value is prohibited';

    /**
     * String to compare against
     * 
     * @var string
     */
    private $result;

    /**
     * State initialization
     * 
     * @param mixed $result String to compare against
     * @return void
     */
    public function __construct($result)
    {
        $this->result = $result;
    }

    /**
     * {@inheritDoc}
     */
    public function isValid($target)
    {
        if ($this->result == $target) {
            $this->violate($this->message);
            return false;
        } else {
            return true;
        }
    }
}

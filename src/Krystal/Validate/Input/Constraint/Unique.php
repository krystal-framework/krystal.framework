<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Input\Constraint;

final class Unique extends AbstractConstraint
{
    /**
     * {@inheritDoc}
     */
    protected $message = 'The record is already taken';

    /**
     * The result of checking for existence
     * 
     * @var boolean
     */
    private $result;

    /**
     * State initialization
     * 
     * @param mixed $result The result of verification call
     * @return void
     */
    public function __construct($result)
    {
        $this->result = (bool) $result;
    }

    /**
     * {@inheritDoc}
     */
    public function isValid($target)
    {
        // The $target is ignored, since the result of method call is only needed here
        if ($this->result === true) {
            $this->violate($this->message);
            return false;
        } else {
            return true;
        }
    }
}

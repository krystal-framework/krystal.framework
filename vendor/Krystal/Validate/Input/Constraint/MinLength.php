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

final class MinLength extends AbstractConstraint
{
    /**
     * {@inheritDoc}
     */
    protected $message = 'A value should contain at least %s characters';

    /**
     * Target minimal length we're comparing against
     * 
     * @var integer
     */
    private $length;

    /**
     * State initialization
     * 
     * @param integer $length
     * @return void
     */
    public function __construct($length)
    {
        $this->length = (int) $length;
    }

    /**
     * Checks whether target is valid
     * 
     * @param string $target
     * @return boolean True if string is blank, False otherwise
     */
    public function isValid($target)
    {
        if (mb_strlen($target, $this->charset) < $this->length) {
            $this->violate(sprintf($this->message, $this->length));
            return false;
        } else {
            return true;
        }
    }
}

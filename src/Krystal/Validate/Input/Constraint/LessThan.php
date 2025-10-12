<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Input\Constraint;

final class LessThan extends AbstractConstraint
{
    /**
     * {@inheritDoc}
     */
    protected $message = 'A value must be less than %s';

    /**
     * Target value
     * 
     * @var integer|float
     */
    private $value;

    /**
     * State initialization
     * 
     * @param float|integer $value
     * @return void
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function isValid($target)
    {
        if ($target < $this->value) {
            return true;
        } else {
            $this->violate(sprintf($this->message, $this->value));
            return false;
        }
    }
}

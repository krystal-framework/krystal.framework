<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Input\Constraint;

final class LessOrEqual extends AbstractConstraint
{
    /**
     * {@inheritDoc}
     */
    protected $message = 'Value must be less or equal';

    /**
     * Target value
     * 
     * @var integer|float
     */
    private $value;

    /**
     * State initialization
     * 
     * @param mixed $value
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
        if ($target >= $this->value) {
            return true;
        } else {
            $this->violate($this->message);
            return false;
        }
    }
}

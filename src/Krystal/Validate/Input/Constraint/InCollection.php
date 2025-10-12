<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Validate\Input\Constraint;

/**
 * Checks whether given string exists in array
 */
final class InCollection extends AbstractConstraint
{
    /**
     * {@inheritDoc}
     */
    protected $message = 'A value does not belong to the collection';

    /**
     * State initialization
     * 
     * @param array $collection
     * @return void
     */
    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    /**
     * {@inheritDoc}
     */
    public function isValid($target)
    {
        if (in_array($target, $this->collection)) {
            return true;
        } else {
            $this->violate($this->message);
            return false;
        }
    }
}

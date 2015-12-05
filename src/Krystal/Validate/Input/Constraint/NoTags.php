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

use Krystal\Security\Filter;

final class NoTags extends AbstractConstraint
{
    /**
     * {@inheritDoc}
     */
    protected $message = 'A value should not contain HTML tags';

    /**
     * Collection of allowed tags
     * 
     * @var array
     */
    private $allowed = array();

    /**
     * State initialization
     * 
     * @param array $allowed An array of allowed tags
     * @return void
     */
    public function __construct(array $allowed = array())
    {
        $this->allowed = $allowed;
    }

    /**
     * {@inheritDoc}
     */
    public function isValid($target)
    {
        if (Filter::hasTags($target, $this->allowed)) {
            $this->violate($this->message);
            return false;
        } else {
            return true;
        }
    }
}

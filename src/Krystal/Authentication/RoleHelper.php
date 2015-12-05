<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Authentication;

class RoleHelper implements RoleHelperInterface
{
    /**
     * Current role
     * 
     * @var string
     */
    private $current;

    /**
     * State initialization
     * 
     * @param string $current
     * @return void
     */
    public function __construct($current)
    {
        $this->current = $current;
    }

    /**
     * Checks whether stored role equals to target one
     * 
     * @param string $role
     * @return boolean
     */
    final public function is($role)
    {
        return $this->current == $role;
    }
}

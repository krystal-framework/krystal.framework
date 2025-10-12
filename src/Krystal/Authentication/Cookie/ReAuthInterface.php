<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Authentication\Cookie;

interface ReAuthInterface
{
    /**
     * Returns user bag
     * 
     * @return \Krystal\Authentication\Cookie\UserBag
     */
    public function getUserBag();

    /**
     * Checks whether data is stored
     * 
     * @return boolean
     */
    public function isStored();

    /**
     * Clears all related data from cookies
     * 
     * @return boolean
     */
    public function clear();

    /**
     * Stores auth data on client machine
     * 
     * @param string $login
     * @param string $passwordHash
     * @return void
     */
    public function store($login, $passwordHash);
}

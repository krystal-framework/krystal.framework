<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Authentication\Protection;

interface AttemptLimitInterface
{
    /**
     * Returns current fail attempt count
     * 
     * @return integer
     */
    public function getCurrentFailAttemptCount();
    
    /**
     * Returns last login
     * 
     * @return string
     */
    public function getLastLogin();

    /**
     * Persist last login
     * 
     * @param string $login
     * @return \Krystal\Authentication\Protection\AttemptLimit
     */
    public function persistLastLogin($login);

    /**
     * Resets the counter
     * 
     * @return void
     */
    public function reset();

    /**
     * Checks whether failure attempts have reached their limits
     * 
     * @return boolean
     */
    public function isReachedLimit();

    /**
     * Increments failure attempt counter
     * 
     * @return void
     */
    public function incrementFailAttempt();
}

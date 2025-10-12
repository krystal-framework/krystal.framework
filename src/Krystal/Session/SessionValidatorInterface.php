<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Session;

interface SessionValidatorInterface
{
    /**
     * Checks whether current session is valid
     * 
     * @param \Krystal\Session\SessionBagInterface $sessionBag
     * @return boolean
     */
    public function isValid(SessionBagInterface $sessionBag);

    /**
     * Writes validation data to the session
     * 
     * @param \Krystal\Session\SessionBagInterface $sessionBag
     * @return void
     */
    public function write(SessionBagInterface $sessionBag);
}

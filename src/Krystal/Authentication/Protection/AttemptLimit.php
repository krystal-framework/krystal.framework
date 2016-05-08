<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Authentication\Protection;

use Krystal\Session\SessionBagInterface;

final class AttemptLimit implements AttemptLimitInterface
{
    /**
     * Session service
     * 
     * @var \Krystal\Session\SessionBagInterface
     */
    private $sessionBag;

    /**
     * Maximal allowed number of fail attempts
     * 
     * @var integer
     */
    private $maxFailAttempts;

    const PARAM_ATTEMPT_NS = 'failed_auth_attempts';
    const PARAM_LAST_LOGIN_NS = 'last_login_attempt';

    /**
     * State initialization
     * 
     * @param \Krystal\Session\SessionBagInterface $sessionBag
     * @param integer $maxFailAttempts
     * @return void
     */
    public function __construct(SessionBagInterface $sessionBag, $maxFailAttempts = 5)
    {
        $this->sessionBag = $sessionBag;
        $this->maxFailAttempts = $maxFailAttempts;
    }

    /**
     * Returns current fail attempt count
     * 
     * @return integer
     */
    public function getCurrentFailAttemptCount()
    {
        if (!$this->sessionBag->has(self::PARAM_ATTEMPT_NS)) {
            $this->reset();
        }

        return $this->sessionBag->get(self::PARAM_ATTEMPT_NS);
    }

    /**
     * Returns last login
     * 
     * @return string
     */
    public function getLastLogin()
    {
        if ($this->sessionBag->has(self::PARAM_LAST_LOGIN_NS)) {
            return $this->sessionBag->get(self::PARAM_LAST_LOGIN_NS);
        } else {
            return null;
        }
    }

    /**
     * Persist last login
     * 
     * @param string $login
     * @return \Krystal\Authentication\Protection\AttemptLimit
     */
    public function persistLastLogin($login)
    {
        $this->sessionBag->set(self::PARAM_LAST_LOGIN_NS, $login);
        return $this;
    }

    /**
     * Resets the counter
     * 
     * @return void
     */
    public function reset()
    {
        $this->sessionBag->removeMany(array(self::PARAM_ATTEMPT_NS, self::PARAM_LAST_LOGIN_NS));
        return $this;
    }

    /**
     * Checks whether failure attempts have reached their limits
     * 
     * @return boolean
     */
    public function isReachedLimit()
    {
        return $this->getCurrentFailAttemptCount() > $this->maxFailAttempts;
    }

    /**
     * Increments failure attempt counter
     * 
     * @return void
     */
    public function incrementFailAttempt()
    {
        $this->sessionBag->set(self::PARAM_ATTEMPT_NS, $this->getCurrentFailAttemptCount() + 1);
        return $this;
    }
}

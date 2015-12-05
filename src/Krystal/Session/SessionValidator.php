<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Session;

final class SessionValidator implements SessionValidatorInterface
{
    const PARAM_REMOTE_ADDR = 'REMOTE_ADDR';
    const PARAM_USER_AGENT = 'HTTP_USER_AGENT';

    /**
     * Server data
     * 
     * @var array
     */
    private $container = array();
    
    /**
     * State initialization
     * 
     * @param array $container Server data
     * @return void
     */
    public function __construct(array $container)
    {
        $this->container = $container;
    }

    /**
     * Checks whether current session is valid
     * 
     * @param \Krystal\Session\SessionBagInterface $sessionBag
     * @return boolean
     */
    public function isValid(SessionBagInterface $sessionBag)
    {
        return $sessionBag->get(self::PARAM_REMOTE_ADDR) === $this->hash($this->getRemoteAddr()) &&
               $sessionBag->get(self::PARAM_USER_AGENT) === $this->hash($this->getUserAgent());
    }

    /**
     * Writes validation data to the session
     * 
     * @param \Krystal\Session\SessionBagInterface $sessionBag
     * @return void
     */
    public function write(SessionBagInterface $sessionBag)
    {
        if ($this->hasRequiredParams()) {

            // Writes hashes, not values themselves
            $sessionBag->set(self::PARAM_REMOTE_ADDR, $this->hash($this->getRemoteAddr()));
            $sessionBag->set(self::PARAM_USER_AGENT, $this->hash($this->getUserAgent()));
        }
    }

    /**
     * Hashes a string
     * 
     * @param string $string
     * @return string
     */
    private function hash($string)
    {
        return md5($string);
    }

    /**
     * Returns current user agent's name
     * 
     * @return string
     */
    private function getUserAgent()
    {
        return $this->container[self::PARAM_USER_AGENT];
    }

    /**
     * Returns current remote address
     * 
     * @return string
     */
    private function getRemoteAddr()
    {
        return $this->container[self::PARAM_REMOTE_ADDR];
    }

    /**
     * Checks whether container data has required keys
     * 
     * @return boolean
     */
    private function hasRequiredParams()
    {
        return array_key_exists(self::PARAM_REMOTE_ADDR, $this->container) && array_key_exists(self::PARAM_USER_AGENT, $this->container);
    }
}

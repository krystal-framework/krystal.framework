<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Cart;

/**
 * Session-based storage adapter
 */
final class SessionAdapter implements StorageAdapterInterface
{
    /** @var string */
    private $sessionKey;

    /**
     * State initialization
     * 
     * @param string $sessionKey Session key for cart storage
     */
    public function __construct($sessionKey = 'shopping_cart')
    {
        $this->sessionKey = $sessionKey;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Save cart data to session
     * 
     * @param array $data Cart data
     */
    public function save(array $data)
    {
        $_SESSION[$this->sessionKey] = $data;
    }

    /**
     * Load cart data from session
     * 
     * @return array Cart data
     */
    public function load()
    {
        return isset($_SESSION[$this->sessionKey]) ? $_SESSION[$this->sessionKey] : [];
    }

    /**
     * Clear cart data from session
     * 
     * @return void
     */
    public function clear()
    {
        unset($_SESSION[$this->sessionKey]);
    }

    /**
     * Get session key
     * 
     * @return string
     */
    public function getSessionKey()
    {
        return $this->sessionKey;
    }
}

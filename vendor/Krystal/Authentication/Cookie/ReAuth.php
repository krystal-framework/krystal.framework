<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Authentication\Cookie;

use Krystal\Http\CookieBagInterface;
use Krystal\Authentication\HashProviderInterface;
use Krystal\Authentication\UserBag;

final class ReAuth implements ReAuthInterface
{
    const CLIENT_LOGIN_KEY = 'k_l';
    const CLIENT_LOGIN_PASSWORD_HASH_KEY = 'k_ph';
    const CLIENT_TOKEN_KEY = 'k_tk';
    const CLIENT_LIFETIME = 630720000;

    /**
     * Cookie bad to read and write cookies
     * 
     * @var \Krystal\Http\CookieBagInterface
     */
    private $cookieBag;

    /**
     * Responsible for generating hash
     * 
     * @var \Krystal\Authentication\HashProviderInterface
     */
    private $hashProvier;

    /**
     * State initialization
     * 
     * @param \Krystal\Http\CookieBagInterface $cookieBag
     * @param \Krystal\Authentication\HashProviderInterface $hashProvider
     * @return void
     */
    public function __construct(CookieBagInterface $cookieBag, HashProviderInterface $hashProvider)
    {
        $this->cookieBag = $cookieBag;
        $this->hashProvider = $hashProvider;
    }

    /**
     * Makes a unique token from login and password
     * 
     * @param string $login
     * @param string $password
     * @return string
     */
    private function makeToken($login, $passwordHash)
    {
        return $this->hashProvider->hash($login.$passwordHash);
    }

    /**
     * Checks whether login and password are valid on client machine
     * 
     * @param string $login
     * @param string $password
     * @param string $token
     * @return boolean
     */
    private function isValidSignature($login, $passwordHash, $token)
    {
        return $this->makeToken($login, $passwordHash) == $token;
    }

    /**
     * Return target keys
     * 
     * @return array
     */
    private function getKeys()
    {
        return array(
            self::CLIENT_LOGIN_KEY, 
            self::CLIENT_LOGIN_PASSWORD_HASH_KEY, 
            self::CLIENT_TOKEN_KEY
        );
    }

    /**
     * Returns user bag
     * 
     * @return \Krystal\Authentication\Cookie\UserBag
     */
    public function getUserBag()
    {
        $userBag = new UserBag();
        $userBag->setLogin($this->cookieBag->get(self::CLIENT_LOGIN_KEY))
                ->setPasswordHash($this->cookieBag->get(self::CLIENT_LOGIN_PASSWORD_HASH_KEY));

        return $userBag;
    }

    /**
     * Checks whether data is stored
     * 
     * @return boolean
     */
    public function isStored()
    {
        foreach ($this->getKeys() as $key) {
            if (!$this->cookieBag->has($key)) {
                return false;
            }
        }

        // Now start checking the signature
        if (!$this->isValidSignature(
            $this->cookieBag->get(self::CLIENT_LOGIN_KEY), 
            $this->cookieBag->get(self::CLIENT_LOGIN_PASSWORD_HASH_KEY),
            $this->cookieBag->get(self::CLIENT_TOKEN_KEY)
        )) {
            return false;
        }

        return true;
    }

    /**
     * Clears all related data from cookies
     * 
     * @return boolean
     */
    public function clear()
    {
        $keys = $this->getKeys();

        foreach ($keys as $key) {
            $this->cookieBag->remove($key);
        }

        return true;
    }

    /**
     * Stores auth data on client machine
     * 
     * @param string $login
     * @param string $passwordHash
     * @return void
     */
    public function store($login, $passwordHash)
    {
        // Data to store on client machine
        $data = array(
            self::CLIENT_LOGIN_KEY => $login,
            self::CLIENT_LOGIN_PASSWORD_HASH_KEY => $passwordHash,
            self::CLIENT_TOKEN_KEY => $this->makeToken($login, $passwordHash)
        );

        foreach ($data as $key => $value) {
            $this->cookieBag->set($key, $value, self::CLIENT_LIFETIME);
        }
    }
}

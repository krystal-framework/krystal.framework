<?php

/**
 * This file is part of the Krystal Framework
 *
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Authentication;

use Krystal\Session\SessionBagInterface;
use Krystal\Authentication\Cookie\RememberMeManager;
use InvalidArgumentException;

final class AuthManager implements AuthManagerInterface
{
    /**
     * Namespace in session storage for authentication
     *
     * @const string
     */
    const AUTH_NAMESPACE = 'krystal_auth';

    /**
     * Session storage
     *
     * @var \Krystal\Session\SessionBagInterface
     */
    private $sessionBag;

    /**
     * Remember me manager
     *
     * @var \Krystal\Authentication\Cookie\RememberMeManager
     */
    private $rememberMe;

    /**
     * Default user provider callback.
     * Note: Receives (string $login) during login(), 
     * and (int|string $userId) during Remember Me validation.
     *
     * @var callable|null
     */
    private $userProvider;

    /**
     * Cached user data for current request
     *
     * @var array|null
     */
    private $currentUser;

    /**
     * State initialization
     *
     * @param \Krystal\Session\SessionBagInterface $sessionBag
     * @param \Krystal\Authentication\Cookie\RememberMeManager $rememberMe
     * @param callable|null $userProvider
     * @return void
     */
    public function __construct(
        SessionBagInterface $sessionBag,
        RememberMeManager $rememberMe,
        $userProvider = null
    ) {
        if ($userProvider !== null && !is_callable($userProvider)) {
            throw new InvalidArgumentException('User provider must be callable');
        }

        $this->sessionBag = $sessionBag;
        $this->rememberMe = $rememberMe;
        $this->userProvider = $userProvider;
    }

    /**
     * Sets or overrides the user provider at runtime
     *
     * @param callable $userProvider
     * @return \Krystal\Authentication\AuthManager
     */
    public function setUserProvider(callable $userProvider)
    {
        $this->userProvider = $userProvider;
        return $this;
    }

    /**
     * Returns the currently configured user provider
     *
     * @return callable|null
     */
    public function getUserProvider()
    {
        return $this->userProvider;
    }

    /**
     * Logs in a user
     *
     * @param string $login
     * @param string $plainPassword
     * @param boolean $remember
     * @param callable|null $userProvider Optional override for this call only
     * @return boolean
     */
    public function login($login, $plainPassword, $remember = false, $userProvider = null)
    {
        $provider = $userProvider !== null ? $userProvider : $this->resolveProvider();
        $user = call_user_func($provider, $login);

        if (!$user || !isset($user['password_hash']) || !password_verify($plainPassword, $user['password_hash'])) {
            return false;
        }

        // Optional: rehash if needed
        if (password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT)) {
            // You can trigger an event/callback here to update the hash in storage
        }

        $this->establishSession($user, $remember);
        return true;
    }

    /**
     * Checks if user is logged in
     *
     * @return boolean
     */
    public function isLoggedIn()
    {
        if ($this->sessionBag->has(self::AUTH_NAMESPACE)) {
            return true;
        }

        $provider = $this->resolveProvider();
        $user = $this->rememberMe->validateAndGetUser($provider);

        if ($user) {
            $this->establishSession($user, false);
            return true;
        }

        return false;
    }

    /**
     * Returns the currently authenticated user
     *
     * @return array|null
     */
    public function getUser()
    {
        if (!$this->isLoggedIn()) {
            return null;
        }

        if ($this->currentUser === null) {
            $this->currentUser = $this->sessionBag->get(self::AUTH_NAMESPACE);
        }

        return $this->currentUser;
    }

    /**
     * Returns the user ID
     *
     * @return string|int|null
     */
    public function getId()
    {
        $user = $this->getUser();
        return $user ? $user['id'] : null;
    }

    /**
     * Returns the user role
     *
     * @return string|null
     */
    public function getRole()
    {
        $user = $this->getUser();
        return $user && isset($user['role']) ? $user['role'] : null;
    }

    /**
     * Returns the user login
     *
     * @return string|null
     */
    public function getLogin()
    {
        $user = $this->getUser();
        return $user ? $user['login'] : null;
    }

    /**
     * Checks if user has any of the specified roles
     *
     * @param array $roles
     * @return boolean
     */
    public function isAllowed(array $roles)
    {
        return $this->isLoggedIn() && in_array($this->getRole(), $roles, true);
    }

    /**
     * Logs out the user
     *
     * @return void
     */
    public function logout()
    {
        $this->sessionBag->remove(self::AUTH_NAMESPACE);
        $this->rememberMe->clear();
        $this->currentUser = null;
    }

    /**
     * Resolves the user provider
     *
     * @return callable
     */
    private function resolveProvider()
    {
        if ($this->userProvider === null) {
            throw new InvalidArgumentException('User provider is not configured. Call setUserProvider() first.');
        }

        return $this->userProvider;
    }

    /**
     * Establishes the session and optionally creates remember-me cookie
     *
     * @param array $user
     * @param boolean $remember
     * @return void
     */
    private function establishSession(array $user, $remember)
    {
        $this->sessionBag->regenerate();

        // Store only safe data in the session
        $safeUser = [
            'id' => $user['id'],
            'login' => $user['login'],
            'role' => isset($user['role']) ? $user['role'] : 'user'
        ];

        $this->sessionBag->set(self::AUTH_NAMESPACE, $safeUser);
        $this->currentUser = $safeUser;

        if ($remember) {
            $this->rememberMe->createCookie($user);
        }
    }
}
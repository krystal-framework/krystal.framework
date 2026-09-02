<?php

/**
 * This file is part of the Krystal Framework
 *
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Authentication\Cookie;

use Krystal\Http\CookieBagInterface;
use InvalidArgumentException;

final class RememberMeManager
{
    /**
     * Default cookie name
     *
     * @const string
     */
    const DEFAULT_COOKIE_NAME = 'k_rm';

    /**
     * Default lifetime in seconds (30 days)
     *
     * @const integer
     */
    const DEFAULT_LIFETIME = 2592000;

    /**
     * Cookie bag instance
     *
     * @var \Krystal\Http\CookieBagInterface
     */
    private $cookieBag;

    /**
     * Secret key for HMAC signature
     *
     * @var string
     */
    private $secretKey;

    /**
     * Cookie name
     *
     * @var string
     */
    private $cookieName;

    /**
     * Cookie lifetime in seconds
     *
     * @var integer
     */
    private $lifetime;

    /**
     * Whether the cookie should be marked as Secure
     *
     * @var boolean
     */
    private $secure;

    /**
     * State initialization
     *
     * @param \Krystal\Http\CookieBagInterface $cookieBag
     * @param string $secretKey
     * @param string $cookieName
     * @param integer $lifetime
     * @param boolean $secure
     * @return void
     */
    public function __construct(
        CookieBagInterface $cookieBag,
        $secretKey,
        $cookieName = self::DEFAULT_COOKIE_NAME,
        $lifetime = self::DEFAULT_LIFETIME,
        $secure = false
    ) {
        if (empty($secretKey) || strlen($secretKey) < 32) {
            throw new InvalidArgumentException('Secret key must be at least 32 characters long');
        }

        $this->cookieBag = $cookieBag;
        $this->secretKey = $secretKey;
        $this->cookieName = $cookieName;
        $this->lifetime = $lifetime;
        $this->secure = (bool) $secure;
    }

    /**
     * Creates a secure, stateless remember-me cookie
     *
     * Expected user array keys:
     * - id
     * - remember_token_version
     *
     * @param array $user
     * @return void
     */
    public function createCookie(array $user)
    {
        if (!isset($user['id']) || !isset($user['remember_token_version'])) {
            throw new InvalidArgumentException('User array must contain "id" and "remember_token_version"');
        }

        $expiration = time() + $this->lifetime;
        $payload = $user['id'] . '|' . $user['remember_token_version'] . '|' . $expiration;
        $signature = hash_hmac('sha256', $payload, $this->secretKey);

        $cookieValue = base64_encode($payload . '.' . $signature);

        // HttpOnly = true is mandatory for auth cookies
        $this->cookieBag->set(
            $this->cookieName,
            $cookieValue,
            $this->lifetime,
            '/',
            $this->secure,
            true   // httpOnly
        );
    }

    /**
     * Validates the cookie and returns the user array if valid
     *
     * @param callable $userProvider
     * @return array|null
     */
    public function validateAndGetUser(callable $userProvider)
    {
        if (!$this->cookieBag->has($this->cookieName)) {
            return null;
        }

        $raw = $this->cookieBag->get($this->cookieName);

        $decoded = base64_decode($raw, true);

        if ($decoded === false || strpos($decoded, '.') === false) {
            $this->clear();
            return null;
        }

        list($payload, $clientSig) = explode('.', $decoded, 2);
        $parts = explode('|', $payload);

        if (count($parts) !== 3) {
            $this->clear();
            return null;
        }

        list($userId, $version, $expiration) = $parts;

        if ((int) $expiration < time()) {
            $this->clear();
            return null;
        }

        $expectedSig = hash_hmac('sha256', $payload, $this->secretKey);

        if (!hash_equals($expectedSig, $clientSig)) {
            $this->clear();
            return null;
        }

        $user = call_user_func($userProvider, $userId);

        if (!$user || !isset($user['remember_token_version'])) {
            $this->clear();
            return null;
        }

        if ((int) $user['remember_token_version'] !== (int) $version) {
            $this->clear();
            return null;
        }

        return $user;
    }

    /**
     * Clears the remember-me cookie
     *
     * @return void
     */
    public function clear()
    {
        if ($this->cookieBag->has($this->cookieName)) {
            $this->cookieBag->remove($this->cookieName);
        }
    }
}
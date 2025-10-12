<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\RateLimiter;

final class RateLimiter
{
    /**
     * An instance of storage implementation
     * 
     * @var \Krystal\Http\PersistentStorageInterface
     */
    private $storage;

    /**
     * The maximum number of allowed requests within the defined $timeWindow.
     * 
     * @var int
     */
    private $limit;

    /**
     * The duration (in seconds) during which the request count is accumulated before resetting.
     * 
     * @var int
     */
    private $timeWindow;

    /**
     * State initialization
     * 
     * @param \Krystal\Http\PersistentStorageInterface $storage Session storage implementation
     * @param int $limit Maximum number of requests allowed
     * @param int $timeWindow Time window in seconds
     * @return void
     */
    public function __construct(PersistentStorageInterface $storage, $limit, $timeWindow)
    {
        $this->storage = $storage;
        $this->limit = $limit;
        $this->timeWindow = $timeWindow;
    }

    /**
     * Returns HTTP headers and status code for rate limit exceeded response
     * 
     * @param array $result
     * @return array
     */
    public function createExceededHeaders(array $result)
    {
        return [
            'HTTP/1.1 429 Too Many Requests',
            sprintf('X-RateLimit-Limit: %s', $result['limit']),
            sprintf('X-RateLimit-Remaining: %s', $result['remaining']),
            sprintf('X-RateLimit-Reset: %s', $result['reset']),
            sprintf('Retry-After: %s', $result['retry'])
        ];
    }

    /**
     * Gets data from storage if available
     * 
     * @param string $key
     * @param mixed Default data to be returned
     * @return mixed
     */
    private function getFromStorage($key, $default)
    {
        if ($this->storage->has($key)) {
            return $this->storage->get($key);
        } else {
            return $default;
        }
    }

    /**
     * Check if the request is allowed
     * 
     * @param string $key Identifier for the rate limit (e.g., user IP or user ID)
     * @return array ['allowed' => bool, 'remaining' => int, 'reset' => int]
     */
    public function check($key)
    {
        $sessionKey = "rate_limit_{$key}";
        $currentTime = time();

        // Get existing data or initialize
        $data = $this->getFromStorage($sessionKey, [
            'count' => 0,
            'start_time' => $currentTime
        ]);

        // Reset if time window has passed
        if ($currentTime - $data['start_time'] > $this->timeWindow) {
            $data = [
                'count' => 0,
                'start_time' => $currentTime
            ];
        }

        // Increment count
        $data['count']++;
        $this->storage->set($sessionKey, $data);

        // Calculate remaining and reset time
        $remaining = max(0, $this->limit - $data['count']);
        $reset = $data['start_time'] + $this->timeWindow;

        return [
            'limit' => $this->limit,
            'allowed' => $data['count'] <= $this->limit,
            'remaining' => $remaining,
            'reset' => $reset,
            'retry' => max(1, $reset - time())
        ];
    }

    /**
     * Get the current rate limit status without incrementing the counter
     * 
     * @param string $key
     * @return array ['allowed' => bool, 'remaining' => int, 'reset' => int]
     */
    public function peek($key)
    {
        $sessionKey = "rate_limit_{$key}";
        $currentTime = time();

        $data = $this->getFromStorage($sessionKey, [
            'count' => 0,
            'start_time' => $currentTime
        ]);

        // Reset if time window has passed
        if ($currentTime - $data['start_time'] > $this->timeWindow) {
            return [
                'allowed' => true,
                'remaining' => $this->limit,
                'reset' => $currentTime + $this->timeWindow
            ];
        }

        $remaining = max(0, $this->limit - $data['count']);
        $reset = $data['start_time'] + $this->timeWindow;

        return [
            'limit' => $this->limit,
            'allowed' => $data['count'] <= $this->limit,
            'remaining' => $remaining,
            'reset' => $reset,
            'retry' => max(1, $reset - time())
        ];
    }
}

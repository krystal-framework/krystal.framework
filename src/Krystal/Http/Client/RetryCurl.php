<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\Client;

use RuntimeException;

/**
 * Extension of Curl that adds automatic retry logic for failed requests.
 *
 * Retries are performed on selected HTTP status codes (e.g. 429, 5xx) and certain
 * cURL-level errors (connection failures, timeouts, etc.) with configurable
 * backoff delays and optional jitter.
 * 
 */
final class RetryCurl extends Curl
{
    /**
     * Maximum number of retry attempts (excluding the initial request)
     *
     * @var int
     */
    private $maxRetries;

    /**
     * HTTP status codes that should trigger a retry
     *
     * @var array
     */
    private $retryStatuses;

    /**
     * Backoff strategy: either an array of delay values (in seconds) indexed by attempt number,
     * or a callable that receives the attempt number (1-based) and returns delay in seconds.
     *
     * @var array|callable
     */
    private $backoffStrategy;

    /**
     * Whether to add small random jitter to backoff delays
     * (helps prevent synchronized retries in distributed systems)
     *
     * @var bool
     */
    private $addJitter;

    /**
     * Creates a new retry-enabled cURL client.
     *
     * @param array   $options         Initial cURL options (passed to parent)
     * @param int     $maxRetries      Maximum number of retries (0 = no retries)
     * @param array   $retryStatuses   HTTP status codes that trigger retry
     * @param array|callable $backoffStrategy  Delay strategy per attempt
     * @param bool    $addJitter       Add random jitter to delays?
     */
    public function __construct(
        array $options = array(),
        $maxRetries = 3,
        array $retryStatuses = array(429, 502, 503, 504),
        $backoffStrategy = array(0, 2, 8, 30),
        $addJitter = true
    ) {
        parent::__construct($options);

        $this->maxRetries      = max(0, (int) $maxRetries);
        $this->retryStatuses   = array_unique($retryStatuses);
        $this->backoffStrategy = $backoffStrategy;
        $this->addJitter       = (bool) $addJitter;
    }

    /**
     * Executes the request with automatic retries applied when conditions are met.
     *
     * @throws \RuntimeException If the underlying cURL execution fails catastrophically
     * @return \Krystal\Http\Client\HttpResponse The final response — either successful or the last failed attempt
     */
    public function exec()
    {
        $attempt = 0;

        do {
            $response = parent::exec();
            $attempt++;

            if (!$this->shouldRetry($response, $attempt)) {
                return $response;
            }

            // Maximum retries reached → return the last (failed) response
            if ($attempt > $this->maxRetries) {
                return $response;
            }

            $delay = $this->calculateDelay($attempt);

            if ($delay > 0) {
                sleep($delay);
            }

        } while (true);
    }

    /**
     * Determines whether the current response/error should trigger another retry attempt.
     *
     * @param HttpResponse $response The response from the most recent attempt
     * @param int          $attempt  Current attempt number (1 = first attempt, 2 = first retry, ...)
     * @return bool True if another retry should be attempted, false otherwise
     */
    private function shouldRetry(HttpResponse $response, $attempt)
    {
        if ($response->hasError()) {
            $errno = isset($response->getError()['code']) ? $response->getError()['code'] : 0;

            // Common retryable cURL errors (network/connection related)
            $retryableErrnos = array(
                7,   // CURLE_COULDNT_CONNECT
                28,  // CURLE_OPERATION_TIMEDOUT
                56,  // CURLE_RECV_ERROR
                55,  // CURLE_SEND_ERROR
                // to add: 52 (CURLE_GOT_NOTHING), 35 (CURLE_SSL_CONNECT_ERROR), etc.
            );

            return in_array($errno, $retryableErrnos, true);
        }

        $statusCode = $response->getStatusCode();
        return in_array($statusCode, $this->retryStatuses, true);
    }

    /**
     * Calculates how many seconds to wait before the next retry attempt.
     *
     * Supports both fixed array-based delays and dynamic callable strategies.
     * Optional jitter can be applied to prevent synchronized retries in distributed systems.
     *
     * @param int $attempt The current attempt number (1-based, after first failure → attempt=2)
     * @return int Number of seconds to sleep (0 = no delay)
     */
    private function calculateDelay($attempt)
    {
        if (is_callable($this->backoffStrategy)) {
            $delay = (int) call_user_func($this->backoffStrategy, $attempt);
        } else {
            $delays = (array) $this->backoffStrategy;
            $index  = min($attempt - 1, count($delays) - 1);
            $delay  = isset($delays[$index]) ? $delays[$index] : 1;
        }

        // Apply jitter only when delay is meaningful
        if ($this->addJitter && $delay > 0) {
            // ±25% random variation — using mt_rand() for better randomness
            $jitterRange = (int) ($delay * 0.25);
            $jitter = mt_rand(-$jitterRange, $jitterRange);
            $delay += $jitter;
            $delay = max(0, $delay);
        }

        return $delay;
    }
}

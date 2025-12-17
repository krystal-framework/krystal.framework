<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\Client;

final class HttpResponse
{
    /**
     * Raw response body
     *
     * @var string
     */
    private $body;

    /**
     * HTTP status code
     *
     * @var int
     */
    private $statusCode;

    /**
     * Response headers
     *
     * @var array
     */
    private $headers = [];

    /**
     * cURL error information
     *
     * @var array|null
     */
    private $error;

    /**
     * cURL transfer info
     *
     * @var array
     */
    private $info;

    /**
     * Constructor
     *
     * @param string $body Response body
     * @param int $statusCode HTTP status code
     * @param array $headers Response headers
     * @param array|null $error cURL error info
     * @param array $info cURL transfer info
     */
    public function __construct($body, $statusCode, array $headers = [], $error = null, array $info = [])
    {
        $this->body = $body;
        $this->statusCode = (int) $statusCode;
        $this->headers = $headers;
        $this->error = $error;
        $this->info = $info;
    }

    /**
     * Convert the response to string (body)
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getBody();
    }

    /**
     * Get the raw response body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Get the HTTP status code
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Determine if the response was successful
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }

    /**
     * Determine if the response was a redirect
     *
     * @return bool
     */
    public function isRedirect()
    {
        return $this->statusCode >= 300 && $this->statusCode < 400;
    }

    /**
     * Determine if the response indicates a client error
     *
     * @return bool
     */
    public function hasClientError()
    {
        return $this->statusCode >= 400 && $this->statusCode < 500;
    }

    /**
     * Determine if the response indicates a server error
     *
     * @return bool
     */
    public function hasServerError()
    {
        return $this->statusCode >= 500 && $this->statusCode < 600;
    }

    /**
     * Determine if the request failed
     *
     * @return bool
     */
    public function hasFailed()
    {
        return $this->hasClientError() || $this->hasServerError() || $this->error !== null;
    }

    /**
     * Get a specific header from the response
     *
     * @param string $name Header name
     * @return string|null
     */
    public function getHeader($name)
    {
        $normalized = strtolower($name);

        foreach ($this->headers as $header => $value) {
            if (strtolower($header) === $normalized) {
                return $value;
            }
        }

        return null;
    }

    /**
     * Get all response headers
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Get the cURL error information
     *
     * @return array|null
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Get the cURL transfer info
     *
     * @return array
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Get the effective URL (after redirects)
     *
     * @return string|null
     */
    public function getEffectiveUrl()
    {
        return $this->info['url'] ?? null;
    }

    /**
     * Get the total request time in seconds
     *
     * @return float|null
     */
    public function getTotalTime()
    {
        return $this->info['total_time'] ?? null;
    }
}

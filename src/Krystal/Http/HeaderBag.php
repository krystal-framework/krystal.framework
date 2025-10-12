<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http;

use Krystal\Http\Response\StatusGenerator;

final class HeaderBag implements HeaderBagInterface
{
    /**
     * Queue of headers
     * 
     * @var array
     */
    private $headers = array();

    /**
     * Returns all request headers
     * 
     * @return array
     */
    public function getAllRequestHeaders()
    {
        return getallheaders();
    }

    /**
     * Determines whether request header has been sent
     * 
     * @param string $header
     * @return boolean
     */
    public function hasRequestHeader($header)
    {
        return array_key_exists($header, $this->getAllRequestHeaders());
    }

    /**
     * Returns request header if present
     * 
     * @param string $header
     * @param boolean $default Default value to be returned in case requested one doesn't exist
     * @return string
     */
    public function getRequestHeader($header, $default = false)
    {
        if ($this->hasRequestHeader($header)) {
            $headers = $this->getAllRequestHeaders();
            return $headers[$header];
        } else {
            return $default;
        }
    }

    /**
     * Sets status code
     * 
     * @param integer $code
     * @return \Krystal\Http\HeaderBag
     */
    public function setStatusCode($code)
    {
        static $sg = null;

        if (is_null($sg)) {
            $sg = new StatusGenerator();
        }

        $status = $sg->generate($code);

        if ($status !== false) {
            $this->append($status);
        }

        return $this;
    }

    /**
     * Set many headers at once and clear all previous ones
     * 
     * @param array $headers
     * @return \Krystal\Http\HeaderBag
     */
    public function setMany(array $headers)
    {
        $this->clear();

        foreach ($headers as $header) {
            $this->append($header);
        }

        return $this;
    }

    /**
     * Clears all previous headers and adds a new one
     * 
     * @param string $header
     * @return \Krystal\Http\HeaderBag
     */
    public function set($header)
    {
        $this->clear();
        $this->append($header);

        return $this;
    }

    /**
     * Appends several headers at once
     * 
     * @param array $headers
     * @return \Krystal\Http\HeaderBag
     */
    public function appendMany(array $headers)
    {
        foreach ($headers as $header) {
            $this->append($header);
        }

        return $this;
    }

    /**
     * Appends a pair
     * 
     * @param string $key
     * @param string $value
     * @return \Krystal\Http\HeaderBag
     */
    public function appendPair($key, $value)
    {
        $header = sprintf('%s: %s', $key, $value);
        $this->append($header);

        return $this;
    }

    /**
     * Append several pairs
     * 
     * @param array $headers
     * @return \Krystal\Http\HeaderBag
     */
    public function appendPairs(array $headers)
    {
        foreach ($headers as $key => $value) {
            $this->appendPair($key, $value);
        }

        return $this;
    }

    /**
     * Appends a header
     * 
     * @param string $header
     * @return \Krystal\Http\HeaderBag
     */
    public function append($header)
    {
        array_push($this->headers, $header);
        return $this;
    }

    /**
     * Checks whether header has been appended before
     * 
     * @param string $header
     * @return boolean
     */
    public function has($header)
    {
        return in_array($this->headers, $header);
    }

    /**
     * Clears the stack
     * 
     * @return \Krystal\Http\HeaderBag
     */
    public function clear()
    {
        $this->headers = array();
        return $this;
    }

    /**
     * Send headers
     * 
     * @param boolean $replace Whether to override a header on collision
     * @return \Krystal\Http\HeaderBag
     */
    public function send($replace = true)
    {
        if (headers_sent()) {
            return;
        }

        foreach ($this->headers as $header) {
            header($header, $replace);
        }

        return $this;
    }
}

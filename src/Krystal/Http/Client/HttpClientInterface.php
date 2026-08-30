<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\Client;

interface HttpClientInterface
{
    /**
     * Performs a HTTP request
     * 
     * @param string $method HTTP method (GET, POST, PUT, PATCH, DELETE, HEAD)
     * @param string $url Target URL
     * @param array $data Data to be sent (query params for GET/HEAD, body for others)
     * @param array|Options $extra Extra options (array or Options instance)
     * @throws \UnexpectedValueException If unknown HTTP method provided
     * @throws \RuntimeException If request fails
     * @return \Krystal\Http\Client\HttpResponse
     */
    public function request($method, $url, array $data = [], $extra = []);

    /**
     * Performs HTTP GET request
     * 
     * @param string $url Target URL
     * @param array $data Query parameters
     * @param array|Options $extra Extra options (array or Options instance)
     * @throws \RuntimeException If request fails
     * @return \Krystal\Http\Client\HttpResponse
     */
    public function get($url, array $data = [], $extra = []);

    /**
     * Performs HTTP POST request
     * 
     * @param string $url Target URL
     * @param array $data POST data (form-encoded)
     * @param array|Options $extra Extra options (array or Options instance)
     * @throws \RuntimeException If request fails
     * @return \Krystal\Http\Client\HttpResponse
     */
    public function post($url, array $data = [], $extra = []);

    /**
     * Performs HTTP PATCH request
     * 
     * @param string $url Target URL
     * @param array $data PATCH data (form-encoded)
     * @param array|Options $extra Extra options (array or Options instance)
     * @throws \RuntimeException If request fails
     * @return \Krystal\Http\Client\HttpResponse
     */
    public function patch($url, array $data = [], $extra = []);

    /**
     * Performs HTTP DELETE request
     * 
     * @param string $url Target URL
     * @param array $data DELETE data (form-encoded)
     * @param array|Options $extra Extra options (array or Options instance)
     * @throws \RuntimeException If request fails
     * @return \Krystal\Http\Client\HttpResponse
     */
    public function delete($url, array $data = [], $extra = []);

    /**
     * Performs HTTP PUT request
     * 
     * @param string $url Target URL
     * @param array $data PUT data (form-encoded)
     * @param array|Options $extra Extra options (array or Options instance)
     * @throws \RuntimeException If request fails
     * @return \Krystal\Http\Client\HttpResponse
     */
    public function put($url, array $data = [], $extra = []);

    /**
     * Performs HTTP HEAD request
     * 
     * @param string $url Target URL
     * @param array $data Query parameters
     * @param array|Options $extra Extra options (array or Options instance)
     * @throws \RuntimeException If request fails
     * @return \Krystal\Http\Client\HttpResponse
     */
    public function head($url, array $data = [], $extra = []);

    /**
     * Set default cURL options
     *
     * @param array $options Default cURL options (CURLOPT_* constants as keys)
     * @return void
     */
    public function setDefaultOptions(array $options);

    /**
     * Get current default options
     *
     * @return array Current default cURL options
     */
    public function getDefaultOptions();
}
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
     * @param array $extra Extra cURL options (CURLOPT_* constants as keys)
     * @throws \UnexpectedValueException If unknown HTTP method provided
     * @throws \RuntimeException If request fails
     * @return string Response body (headers for HEAD requests)
     */
    public function request($method, $url, array $data = array(), array $extra = array());

    /**
     * Performs HTTP GET request
     * 
     * @param string $url Target URL
     * @param array $data Query parameters
     * @param array $extra Extra cURL options (CURLOPT_* constants as keys)
     * @throws \RuntimeException If request fails
     * @return string Response body
     */
    public function get($url, array $data = array(), array $extra = array());

    /**
     * Performs HTTP POST request
     * 
     * @param string $url Target URL
     * @param array $data POST data (form-encoded)
     * @param array $extra Extra cURL options (CURLOPT_* constants as keys)
     * @throws \RuntimeException If request fails
     * @return string Response body
     */
    public function post($url, array $data = array(), array $extra = array());

    /**
     * Performs HTTP PATCH request
     * 
     * @param string $url Target URL
     * @param array $data PATCH data (form-encoded)
     * @param array $extra Extra cURL options (CURLOPT_* constants as keys)
     * @throws \RuntimeException If request fails
     * @return string Response body
     */
    public function patch($url, array $data = array(), array $extra = array());

    /**
     * Performs HTTP DELETE request
     * 
     * @param string $url Target URL
     * @param array $data DELETE data (form-encoded)
     * @param array $extra Extra cURL options (CURLOPT_* constants as keys)
     * @throws \RuntimeException If request fails
     * @return string Response body
     */
    public function delete($url, array $data = array(), array $extra = array());

    /**
     * Performs HTTP PUT request
     * 
     * @param string $url Target URL
     * @param array $data PUT data (form-encoded)
     * @param array $extra Extra cURL options (CURLOPT_* constants as keys)
     * @throws \RuntimeException If request fails
     * @return string Response body
     */
    public function put($url, array $data = array(), array $extra = array());

    /**
     * Performs HTTP HEAD request
     * 
     * @param string $url Target URL
     * @param array $data Query parameters
     * @param array $extra Extra cURL options (CURLOPT_* constants as keys)
     * @throws \RuntimeException If request fails
     * @return string Response headers (empty body)
     */
    public function head($url, array $data = array(), array $extra = array());

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

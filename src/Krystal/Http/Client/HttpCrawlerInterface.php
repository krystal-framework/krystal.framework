<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\Client;

interface HttpCrawlerInterface
{
    /**
     * Performs a HTTP request
     * 
     * @param string $method
     * @param string $url Target URL
     * @param array $data Data to be sent
     * @param array $extra Extra options
     * @param \UnexpectedValueException If unknown HTTP method provided
     * @return mixed
     */
    public function request($method, $url, array $data = array(), array $extra = array());

    /**
     * Performs HTTP GET request
     * 
     * @param string $url Target URL
     * @param array $data Data to be sent
     * @param array $extra Extra options
     * @return mixed
     */
    public function get($url, array $data = array(), array $extra = array());

    /**
     * Performs HTTP GET request
     * 
     * @param string $url Target URL
     * @param array $data Data to be sent
     * @param array $extra Extra options
     * @return mixed
     */
    public function post($url, $data = array(), array $extra = array());

    /**
     * Performs HTTP PATCH request
     * 
     * @param string $url Target URL
     * @param array $data Data to be sent
     * @param array $extra Extra options
     * @return mixed
     */
    public function patch($url, array $data = array(), array $extra = array());

    /**
     * Performs HTTP DELETE request
     * 
     * @param string $url Target URL
     * @param array $data Data to be sent
     * @param array $extra Extra options
     * @return mixed
     */
    public function delete($url, array $data = array(), array $extra = array());

    /**
     * Performs HTTP PUT request
     * 
     * @param string $url Target URL
     * @param array $data Data to be sent
     * @param array $extra Extra options
     * @return mixed
     */
    public function put($url, array $data = array(), array $extra = array());

    /**
     * Performs HTTP HEAD request
     * 
     * @param string $url Target URL
     * @param array $data Data to be sent
     * @param array $extra Extra options
     * @return mixed
     */
    public function head($url, array $data = array(), array $extra = array());
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\Client;

interface HttpCrawlerInterface
{
    /**
     * Returns information about the last transfer
     * 
     * @param integer $opt
     * @return mixed
     */
    public function getInfo($opt = 0);

    /**
     * Return error messages if any
     * 
     * @return array
     */
    public function getErrors();

    /**
     * Performs a HTTP request
     * 
     * @param string $method
     * @param string $url Target URL
     * @param array $data Data to be sent
     * @param string $prepend The character to be prepended to query string for GET request
     * @param array $extra Extra options
     * @param \UnexpectedValueException If unknown HTTP method provided
     * @return mixed
     */
    public function request($method, $url, array $data = array(), $prepend = '?', array $extra = array());

    /**
     * Performs HTTP GET request
     * 
     * @param string $url Target URL
     * @param array $data Data to be sent
     * @param string $prepend The character to be prepended to query string
     * @param array $extra Extra options
     * @return mixed
     */
    public function get($url, array $data = array(), $prepend = '?', array $extra = array());

    /**
     * Performs HTTP GET request
     * 
     * @param string $url Target URL
     * @param array $data Data to be sent
     * @param array $extra Extra options
     * @return mixed
     */
    public function post($url, array $data = array(), array $extra = array());

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
     * @param string $prepend The character to be prepended to query string
     * @return mixed
     */
    public function head($url, array $data = array(), $prepend = '?', array $extra = array());
}

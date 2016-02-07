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

use UnexpectedValueException;

final class CurlHttplCrawler implements HttpCrawlerInterface
{
    /**
     * cURL handler
     * 
     * @var \Krystal\Http\Client\CurlInterface
     */
    private $curl;

    /**
     * State initialization
     * 
     * @param \Krystal\Http\Client\CurlInterface $curl 
     * @return void
     */
    public function __construct(CurlInterface $curl)
    {
        $this->curl = $curl;
    }

    /**
     * Builds an instance
     * 
     * @return \Krystal\Http\Client\CurlHttplCrawler
     */
    public static function factory()
    {
        return new self(new Curl());
    }

    /**
     * Returns information about the last transfer
     * 
     * @param integer $opt
     * @return mixed
     */
    public function getInfo($opt = 0)
    {
        return $this->curl->getInfo($opt);
    }

    /**
     * Return error messages if any
     * 
     * @return array
     */
    public function getErrors()
    {
        return $this->curl->getErrors();
    }

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
    public function request($method, $url, array $data = array(), $prepend = '?', array $extra = array())
    {
        switch (strtoupper($method)) {
            case 'POST':
                return $this->post($url, $data, $extra);
            case 'GET':
                return $this->get($url, $data, $prepend, $extra);
            case 'PATCH':
                return $this->patch($url, $data, $extra);
            case 'HEAD':
                return $this->head($url, $data, $prepend, $extra);
            case 'PUT':
                return $this->put($url, $data, $prepend, $extra);
            case 'DELETE':
                return $this->delete($url, $data, $prepend, $extra);
            default:
                throw new UnexpectedValueException(sprintf('Unsupported or unknown HTTP method provided "%s"', $method));
        }
    }

    /**
     * Executes cURL
     * 
     * @param array $options
     * @param array $extra
     * @return mixed
     */
    private function exec(array $options, array $extra)
    {
        $this->curl->init(array_merge($options, $extra));
        return $this->curl->exec();
    }

    /**
     * Performs HTTP GET request
     * 
     * @param string $url Target URL
     * @param array $data Data to be sent
     * @param string $prepend The character to be prepended to query string
     * @param array $extra Extra options
     * @return mixed
     */
    public function get($url, array $data = array(), $prepend = '?', array $extra = array())
    {
        return $this->exec(array(
            CURLOPT_URL => $url . $prepend . http_build_query($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false

        ), $extra);
    }

    /**
     * Performs HTTP POST request
     * 
     * @param string $url Target URL
     * @param array $data Data to be sent
     * @param array $extra Extra options
     * @return mixed
     */
    public function post($url, array $data = array(), array $extra = array())
    {
        return $this->exec(array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_POST => count($data),
            CURLOPT_POSTFIELDS => http_build_query($data)

        ), $extra);
    }

    /**
     * Performs HTTP PATCH request
     * 
     * @param string $url Target URL
     * @param array $data Data to be sent
     * @param array $extra Extra options
     * @return mixed
     */
    public function patch($url, array $data = array(), array $extra = array())
    {
        return $this->exec(array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS => http_build_query($data)

        ), $extra);
    }

    /**
     * Performs HTTP DELETE request
     * 
     * @param string $url Target URL
     * @param array $data Data to be sent
     * @param array $extra Extra options
     * @return mixed
     */
    public function delete($url, array $data = array(), array $extra = array())
    {
        return $this->exec(array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_POSTFIELDS => http_build_query($data)

        ), $extra);
    }

    /**
     * Performs HTTP PUT request
     * 
     * @param string $url Target URL
     * @param array $data Data to be sent
     * @param array $extra Extra options
     * @return mixed
     */
    public function put($url, array $data = array(), array $extra = array())
    {
        return $this->exec(array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => http_build_query($data)

        ), $extra);
    }

    /**
     * Performs HTTP HEAD request
     * 
     * @param string $url Target URL
     * @param array $data Data to be sent
     * @param array $extra Extra options
     * @param string $prepend The character to be prepended to query string
     * @return mixed
     */
    public function head($url, array $data = array(), $prepend = '?', array $extra = array())
    {
        return $this->exec(array(
            CURLOPT_URL => $url . $prepend . http_build_query($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_NOBODY => true,
            CURLOPT_POSTFIELDS => http_build_query($data)

        ), $extra);
    }
}

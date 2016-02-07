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
     * @param \UnexpectedValueException If unknown HTTP method provided
     * @return mixed
     */
    public function request($method, $url, array $data = array(), $prepend = '?')
    {
        switch (strtoupper($method)) {
            case 'POST':
                return $this->post($url, $data);
            case 'GET':
                return $this->get($url, $data, $prepend);
            default:
                throw new UnexpectedValueException(sprintf('Unsupported or unknown HTTP method provided "%s"', $method));
        }
    }

    /**
     * Performs HTTP GET request
     * 
     * @param string $url Target URL
     * @param array $data Data to be sent
     * @param string $prepend The character to be prepended to query string
     * @return mixed
     */
    public function get($url, array $data = array(), $prepend = '?')
    {
        $this->curl->init(array(
            CURLOPT_URL => $url . $prepend . http_build_query($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)'
        ));

        return $this->curl->exec();
    }

    /**
     * Performs HTTP POST request
     * 
     * @param string $url Target URL
     * @param array $data Data to be sent
     * @return mixed
     */
    public function post($url, array $data = array())
    {
        $this->curl->init(array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_POST => count($data),
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)'
        ));

        return $this->curl->exec();
    }
}

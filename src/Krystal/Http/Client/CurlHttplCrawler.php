<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\Client;

use UnexpectedValueException;

final class CurlHttplCrawler implements HttpCrawlerInterface
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
    public function request($method, $url, array $data = array(), array $extra = array())
    {
        switch (strtoupper($method)) {
            case 'POST':
                return $this->post($url, $data, $extra);
            case 'GET':
                return $this->get($url, $data, $extra);
            case 'PATCH':
                return $this->patch($url, $data, $extra);
            case 'HEAD':
                return $this->head($url, $data, $extra);
            case 'PUT':
                return $this->put($url, $data, $extra);
            case 'DELETE':
                return $this->delete($url, $data, $extra);
            default:
                throw new UnexpectedValueException(sprintf('Unsupported or unknown HTTP method provided "%s"', $method));
        }
    }

    /**
     * Performs HTTP GET request
     * 
     * @param string $url Target URL
     * @param array $data Data to be sent
     * @param array $extra Extra options
     * @return mixed
     */
    public function get($url, array $data = array(), array $extra = array())
    {
        if (!empty($data)) {
            $url = $url . '?' . http_build_query($data);
        }

		$curl = new Curl(array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false
        ), $extra);

        return $curl->exec();
    }

    /**
     * Performs HTTP POST request
     * 
     * @param string $url Target URL
     * @param array $data Data to be sent
     * @param array $extra Extra options
     * @return mixed
     */
    public function post($url, $data = array(), array $extra = array())
    {
        $curl = new Curl(array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_POST => count($data),
            CURLOPT_POSTFIELDS => http_build_query($data)

        ), $extra);

        return $curl->exec();
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
        $curl = new Curl(array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS => http_build_query($data)
        ), $extra);

        return $curl->exec();
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
        $curl = new Curl(array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_POSTFIELDS => http_build_query($data)
        ), $extra);

        return $curl->exec();
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
        $curl = new Curl(array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => http_build_query($data)
        ), $extra);

        return $curl->exec();
    }

    /**
     * Performs HTTP HEAD request
     * 
     * @param string $url Target URL
     * @param array $data Data to be sent
     * @param array $extra Extra options
     * @return mixed
     */
    public function head($url, array $data = array(), array $extra = array())
    {
        if (!empty($data)) {
            $url = $url . '?' . http_build_query($data);
        }

        $curl = new Curl(array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_NOBODY => true,
            CURLOPT_POSTFIELDS => http_build_query($data)
        ), $extra);

        return $curl->exec();
    }
}

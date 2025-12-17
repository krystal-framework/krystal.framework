<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\Client;

use UnexpectedValueException;
use RuntimeException;

final class HttpClient implements HttpClientInterface
{
    /**
     * Default cURL options
     *
     * @var array
     */
    private $defaultOptions = array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_USERAGENT => 'Krystal HTTP Client'
    );

    /**
     * Performs a HTTP request
     * 
     * @param string $method
     * @param string $url Target URL
     * @param array $data Data to be sent
     * @param array $extra Extra cURL options
     * @throws \UnexpectedValueException If unknown HTTP method provided
     * @throws \RuntimeException If request fails
     * @return string Response body
     */
    public function request($method, $url, array $data = array(), array $extra = array())
    {
        $method = strtoupper($method);

        switch ($method) {
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
                throw new UnexpectedValueException(sprintf('Unsupported HTTP method: "%s"', $method));
        }
    }

    /**
     * Performs HTTP GET request
     * 
     * @param string $url Target URL
     * @param array $data Query parameters
     * @param array $extra Extra cURL options
     * @throws \RuntimeException If request fails
     * @return string Response body
     */
    public function get($url, array $data = array(), array $extra = array())
    {
        if (!empty($data)) {
            $url = $this->appendQueryString($url, $data);
        }

        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPGET => true
        );

        return $this->executeRequest($options, $extra);
    }

    /**
     * Performs HTTP POST request
     * 
     * @param string $url Target URL
     * @param array $data POST data
     * @param array $extra Extra cURL options
     * @throws \RuntimeException If request fails
     * @return string Response body
     */
    public function post($url, array $data = array(), array $extra = array())
    {
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data)
        );

        return $this->executeRequest($options, $extra);
    }

    /**
     * Performs HTTP PATCH request
     * 
     * @param string $url Target URL
     * @param array $data PATCH data
     * @param array $extra Extra cURL options
     * @throws \RuntimeException If request fails
     * @return string Response body
     */
    public function patch($url, array $data = array(), array $extra = array())
    {
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS => http_build_query($data)
        );

        return $this->executeRequest($options, $extra);
    }

    /**
     * Performs HTTP DELETE request
     * 
     * @param string $url Target URL
     * @param array $data DELETE data
     * @param array $extra Extra cURL options
     * @throws \RuntimeException If request fails
     * @return string Response body
     */
    public function delete($url, array $data = array(), array $extra = array())
    {
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_POSTFIELDS => http_build_query($data)
        );

        return $this->executeRequest($options, $extra);
    }

    /**
     * Performs HTTP PUT request
     * 
     * @param string $url Target URL
     * @param array $data PUT data
     * @param array $extra Extra cURL options
     * @throws \RuntimeException If request fails
     * @return string Response body
     */
    public function put($url, array $data = array(), array $extra = array())
    {
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => http_build_query($data)
        );

        return $this->executeRequest($options, $extra);
    }

    /**
     * Performs HTTP HEAD request
     * 
     * @param string $url Target URL
     * @param array $data Query parameters
     * @param array $extra Extra cURL options
     * @throws \RuntimeException If request fails
     * @return string Response headers (empty body for HEAD)
     */
    public function head($url, array $data = array(), array $extra = array())
    {
        if (!empty($data)) {
            $url = $this->appendQueryString($url, $data);
        }

        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_NOBODY => true,
            CURLOPT_HEADER => true // Return headers
        );

        return $this->executeRequest($options, $extra);
    }

    /**
     * Execute cURL request with merged options
     *
     * @param array $methodOptions Method-specific options
     * @param array $extraOptions User-provided extra options
     * @throws \RuntimeException If request fails
     * @return string Response
     */
    private function executeRequest(array $methodOptions, array $extraOptions = array())
    {
        // Merge: defaults < method options < user options (user wins)
        $options = array_replace($this->defaultOptions, $methodOptions, $extraOptions);

        $curl = new Curl($options);
        $result = $curl->exec();

        if ($result === false) {
            $error = $curl->getError();
            $errno = $curl->getErrno();
            throw new RuntimeException(sprintf('HTTP request failed: %s (cURL error %d)', $error, $errno));
        }

        return $result;
    }

    /**
     * Append query string to URL
     *
     * @param string $url
     * @param array $data
     * @return string
     */
    private function appendQueryString($url, array $data)
    {
        $query = http_build_query($data);
        $separator = (strpos($url, '?') === false) ? '?' : '&';

        return $url . $separator . $query;
    }

    /**
     * Set default cURL options
     *
     * @param array $options
     * @return void
     */
    public function setDefaultOptions(array $options)
    {
        $this->defaultOptions = array_replace($this->defaultOptions, $options);
    }

    /**
     * Get current default options
     *
     * @return array
     */
    public function getDefaultOptions()
    {
        return $this->defaultOptions;
    }
}

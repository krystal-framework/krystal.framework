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
use InvalidArgumentException;

final class HttpClient implements HttpClientInterface
{
    /**
     * Default options
     * 
     * @var array
     */
    private $defaultOptions = [];

    /**
     * Default retry configuration (can be overridden per request)
     * 
     * @var array
     */
    private $retryConfig = array(
        'enabled'        => false, // retry disabled by default
        'maxRetries'     => 3,
        'retryStatuses'  => array(429, 502, 503, 504),
        'backoffStrategy'=> array(0, 2, 8, 30),
        'addJitter'      => true,
    );

    /**
     * State initialization
     *
     * @param array $options Default cURL options (will be merged with internal defaults)
     * @param array $retryConfig Default retry settings (or empty array to disable retry globally)
     */
    public function __construct(array $options = array(), array $retryConfig = array())
    {
        // Merge user-provided cURL defaults
        $this->defaultOptions = array_replace($this->defaultOptions, $options);

        // Merge / override retry defaults (if user wants global retry)
        if (!empty($retryConfig)) {
            $this->retryConfig = array_replace($this->retryConfig, $retryConfig);
        }
    }
    
    /**
     * Creates appropriate cURL instance
     * 
     * @param array $options cURL options
     * @return mixed
     */
    private function createCurl(array $options)
    {
        if ($this->retryConfig['enabled'] == true) {
            return new RetryCurl(
                $options,
                $this->retryConfig['maxRetries'],
                $this->retryConfig['retryStatuses'],
                $this->retryConfig['backoffStrategy'],
                $this->retryConfig['addJitter']
            );
        } else {
            return new Curl($options);
        }
    }

    /**
     * Download a binary file and save it to disk
     *
     * @param string $url The URL to download
     * @param string $saveToPath Path to save the downloaded file
     * @param array  $extra Extra cURL options
     * @return bool true on success, false on failure (cURL error or non-2xx status)
     * @throws \RuntimeException If cannot open file for writing
     */
    public function download($url, $saveToPath, array $extra = [])
    {
        // 1. Open a file pointer for writing
        $fp = fopen($saveToPath, 'w+');

        if ($fp === false) {
            throw new RuntimeException("Could not open local file for writing.");
        }

        try {
            $curl = $this->createCurl([
                CURLOPT_URL            => $url,
                CURLOPT_FILE           => $fp,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_BINARYTRANSFER => true,
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_TIMEOUT        => 300,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_AUTOREFERER    => true,
                CURLOPT_ENCODING       => '', // Accept all encodings
                CURLOPT_USERAGENT      => 'Krystal HTTP Client (PHP 5.6+)',
            ]);

            // Merge any extra options (useful for headers, auth, etc.)
            if (!empty($extra)) {
                $curl->setOptions($extra);
            }

            $result = $curl->exec();

            // 2. Check for cURL-level errors
            if ($result->hasError()) {
                $errorMsg = $result->getError(); // assuming getError() returns string or array

                if (is_array($errorMsg)) {
                    $errorMsg = print_r($errorMsg, true);
                }

                throw new RuntimeException("cURL error while downloading '{$url}': {$errorMsg}");
            }

            if (!$result->isSuccessful()) {
                throw new RuntimeException("Download failed with HTTP status {$result->getStatusCode()}"); 
            }

            return true;
        } finally {
            // Always close the file handle, even if an exception was thrown
            fclose($fp);
            unset($curl);
        }
    }

    /**
     * Performs a HTTP request
     * 
     * @param string $method
     * @param string $url Target URL
     * @param array $data Data to be sent
     * @param array $extra Extra cURL options
     * @throws \UnexpectedValueException If unknown HTTP method provided
     * @throws \RuntimeException If request fails
     * @return \Krystal\Http\Client\HttpResponse
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
     * Perform a JSON request with automatic Content-Type header
     * 
     * This method sends JSON data for methods that support request bodies (POST, PUT, PATCH, DELETE).
     * It automatically sets the Content-Type and Accept headers to application/json.
     * 
     * @param string $method HTTP method (POST, PUT, PATCH, DELETE)
     * @param string $url Target URL
     * @param array $data Data to encode as JSON
     * @param array $extra Additional cURL options
     * @throws \InvalidArgumentException If method doesn't exist or JSON encoding fails
     * @return \Krystal\Http\Client\HttpResponse
     */
    public function jsonRequest($method, $url, array $data = [], array $extra = [])
    {
        $method = strtoupper($method);

        // Validate allowed methods for JSON
        $allowedMethods = ['POST', 'PUT', 'PATCH', 'DELETE'];

        if (!in_array($method, $allowedMethods)) {
            throw new InvalidArgumentException(sprintf(
                'JSON body not allowed for %s method. Allowed: %s', $method, implode(', ', $allowedMethods)
            ));
        }

        // Merge headers: default JSON headers < user headers (user wins)
        $jsonHeaders = ['Content-Type: application/json', 'Accept: application/json'];
        $userHeaders = $extra[CURLOPT_HTTPHEADER] ?? [];

        // Avoid potential header duplication
        $extra[CURLOPT_HTTPHEADER] = array_unique(array_merge($jsonHeaders, $userHeaders));

        // Encode data to JSON
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('Failed to encode data to JSON: ' . json_last_error_msg());
        }

        $extra[CURLOPT_POSTFIELDS] = $json;

        return $this->{strtolower($method)}($url, [], $extra);
    }

    /**
     * Performs HTTP GET request
     * 
     * @param string $url Target URL
     * @param array $data Query parameters
     * @param array $extra Extra cURL options
     * @throws \RuntimeException If request fails
     * @return \Krystal\Http\Client\HttpResponse
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
     * @return \Krystal\Http\Client\HttpResponse
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
     * @return \Krystal\Http\Client\HttpResponse
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
     * @return \Krystal\Http\Client\HttpResponse
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
     * @return \Krystal\Http\Client\HttpResponse
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
     * @return \Krystal\Http\Client\HttpResponse
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
     * Iterates over all pages of a paginated API and calls the provided callback
     * for each page's items.
     *
     * All configuration is passed via a single $config array.
     *
     * @param array $config All settings (url, method, pagination keys, per_page, etc.)
     * @param callable $callback Required: function(array $itemsData, int $page): void
     * @return void
     *
     * @throws \InvalidArgumentException If required config keys are missing
     * @throws \RuntimeException         On fetch or parsing failure
     */
    public function processPaginatedResponse(array $config, callable $callback)
    {
        // Required
        if (empty($config['url'])) {
            throw new InvalidArgumentException("Missing required 'url' in config");
        }

        // Defaults
        $defaults = [
            'method'            => 'GET',
            'payload'           => [], // base body data for POST
            'query'             => [], // base query params (for GET or extra POST params)
            'extra'             => [], // extra cURL options
            'per_page'          => 100, // number of items per page (value)
            'max_pages'         => null,
            'data_key'          => 'data',
            'next_page_key'     => 'next_page_url',
            'page_key'          => 'page',
            'total_pages_key'   => 'total_pages',
            'total_count_key'   => 'total_count',
            'per_page_param'    => 'per_page', // name of the param sent to API
            'page_param'        => 'page', // name of the page param sent to API
        ];

        $config = array_replace($defaults, $config);
        $method = strtoupper($config['method']);

        if (!in_array($method, ['GET', 'POST'])) {
            throw new InvalidArgumentException("Only 'GET' and 'POST' methods are supported");
        }

        $currentPage = 1;
        $currentUrl  = $config['url'];

        do {
            // Prepare page-specific parameters
            $pageParams = $config['query'];
            $pageParams[$config['per_page_param']] = $config['per_page'];
            $pageParams[$config['page_param']]     = $currentPage;

            // Fetch
            if ($method === 'POST') {
                $pagePayload = array_replace($config['payload'], $pageParams);
                $response = $this->jsonRequest('POST', $currentUrl, $pagePayload, $config['extra']);
            } else {
                $response = $this->get($currentUrl, $pageParams, $config['extra']);
            }

            if (!$response->isSuccessful()) {
                throw new RuntimeException(
                    "Page {$currentPage} failed with status " . $response->getStatusCode()
                );
            }

            $data = $response->parseJSON();

            if (!isset($data[$config['data_key']]) || !is_array($data[$config['data_key']])) {
                break;
            }

            $itemsData = $data[$config['data_key']];
            call_user_func($callback, $itemsData, $currentPage);

            // Next page detection
            $hasNext = false;

            if (!empty($data[$config['next_page_key']])) {
                $currentUrl = $data[$config['next_page_key']];
                $hasNext = true;
            } else {
                $currentPageNum = isset($data[$config['page_key']]) ? (int)$data[$config['page_key']] : $currentPage;

                if (isset($data[$config['total_pages_key']])) {
                    $hasNext = $currentPageNum < (int)$data[$config['total_pages_key']];
                } elseif (isset($data[$config['total_count_key']])) {
                    $totalCount = (int)$data[$config['total_count_key']];
                    $hasNext = $currentPageNum < ceil($totalCount / $config['per_page']);
                }

                if ($hasNext) {
                    $currentPage++;
                }
            }

            if ($config['max_pages'] !== null && $currentPage > $config['max_pages']) {
                break;
            }

            if (!$hasNext) {
                break;
            }

            usleep(250000);

        } while (true);
    }

    /**
     * Execute cURL request with merged options
     *
     * @param array $methodOptions Method-specific options
     * @param array $extraOptions User-provided extra options
     * @throws \RuntimeException If request fails
     * @return \Krystal\Http\Client\HttpResponse
     */
    private function executeRequest(array $methodOptions, array $extraOptions = array())
    {
        // Merge: method options < user options (user wins)
        $options = array_replace($this->defaultOptions, $methodOptions, $extraOptions);

        $curl = $this->createCurl($options);
        $result = $curl->exec();

        if ($result->hasError()) {
            $error = $result->getError();
            $msg = $error['message'] ?? 'Unknown error';
            $code = $error['code'] ?? 0;

            throw new RuntimeException(sprintf('HTTP request failed: %s (cURL error %d)', $msg, $code));            
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

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\Client;

use InvalidArgumentException;

final class Options
{
    /**
     * Raw high-level options configuration
     * 
     * @var array
     */
    private $options = [];

    /**
     * State initialization
     * 
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * Resolves high-level options into standard cURL options and HTTP headers
     * 
     * @return array
     */
    public function resolve()
    {
        $curlOptions = [];
        $headers = [];

        // 1. Standard headers mapping ('headers' => ['X-Custom' => 'value'])
        if (isset($this->options['headers']) && is_array($this->options['headers'])) {
            foreach ($this->options['headers'] as $name => $value) {
                $headers[] = "{$name}: {$value}";
            }
        }

        // 2. Authentication abstraction ('auth' => ['type' => 'Bearer', 'token' => '...'])
        if (isset($this->options['auth']) && is_array($this->options['auth'])) {
            $auth = $this->options['auth'];
            $type = strtolower($auth['type'] ?? 'bearer');

            switch ($type) {
                case 'bearer':
                    $token = $auth['token'] ?? ($auth[0] ?? '');
                    if (!empty($token)) {
                        $headers[] = "Authorization: Bearer {$token}";
                    }
                    break;

                case 'basic':
                    $username = $auth['username'] ?? ($auth[0] ?? '');
                    $password = $auth['password'] ?? ($auth[1] ?? '');
                    $curlOptions[CURLOPT_USERPWD] = "{$username}:{$password}";
                    break;

                default:
                    throw new InvalidArgumentException(sprintf('Unsupported authentication type: "%s"', $type));
            }
        }

        // 3. Timeouts
        if (isset($this->options['timeout'])) {
            $curlOptions[CURLOPT_TIMEOUT] = (int)$this->options['timeout'];
        }

        if (isset($this->options['connect_timeout'])) {
            $curlOptions[CURLOPT_CONNECTTIMEOUT] = (int)$this->options['connect_timeout'];
        }

        // 4. User Agent
        if (isset($this->options['user_agent'])) {
            $curlOptions[CURLOPT_USERAGENT] = (string)$this->options['user_agent'];
        }

        // 5. Pass through any raw cURL options if provided directly
        foreach ($this->options as $key => $value) {
            if (is_int($key)) {
                $curlOptions[$key] = $value;
            }
        }

        // Consolidate headers if any were collected
        if (!empty($headers)) {
            $existingHeaders = $curlOptions[CURLOPT_HTTPHEADER] ?? [];
            $curlOptions[CURLOPT_HTTPHEADER] = array_merge($existingHeaders, $headers);
        }

        return $curlOptions;
    }

    /**
     * Get underlying raw array configuration
     * 
     * @return array
     */
    public function toArray()
    {
        return $this->options;
    }
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\Client;

use RuntimeException;
use InvalidArgumentException;

final class Curl implements CurlInterface
{
    /**
     * cURL handle (resource|CurlHandle|null)
     *
     * @var mixed
     */
    private $ch = null;

    /**
     * Errors from last execution
     *
     * @var array
     */
    private $errors = array();

    /**
     * State initialization
     *
     * @param array $options
     * @throws \RuntimeException if cURL extension is not installed
     * @return void
     */
    public function __construct(array $options = array())
    {
        if (!function_exists('curl_init')) {
            throw new RuntimeException(
                'To use cURL HTTP adapter, you must have the curl extension installed'
            );
        }

        $this->init($options);
    }

    /**
     * Safely clone cURL handle
     * 
     * @return void
     */
    public function __clone()
    {
        if (PHP_MAJOR_VERSION >= 8) {
            throw new RuntimeException('Cloning CurlHandle is not supported in PHP 8+');
        }

        if ($this->isHandleValid()) {
            $copy = curl_copy_handle($this->ch);

            if ($copy === false) {
                throw new RuntimeException('Failed to clone cURL handle');
            }

            $this->ch = $copy;
            $this->errors = array(); // Also clone should reset errors
        }
    }

    /**
     * Destructor
     * 
     * @return void
     */
    public function __destruct()
    {
        $this->close();
    }
    
    /**
     * Prevent serialization of cURL handle
     * 
     * @throws \RuntimeException Always
     * @return array
     */
    public function __sleep()
    {
        throw new RuntimeException('Curl objects cannot be serialized');
    }

    /**
     * Prevent unserialization of cURL handle
     * 
     * @throws \RuntimeException Always
     * @return void
     */
    public function __wakeup()
    {
        throw new RuntimeException('Curl objects cannot be unserialized');
    }

    /**
     * Initialize cURL session
     *
     * @param array $options
     * @throws \RuntimeException If failed to initialize
     * @return void
     */
    public function init(array $options = array())
    {
        $this->close();
        $this->ch = curl_init();

        if ($this->ch === false) {
            throw new RuntimeException('Failed to initialize cURL');
        }

        // Always apply defaults first
        $this->applyDefaults();

        // Then apply custom options (which can override defaults)
        if (!empty($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * Reset cURL handle state
     * 
     * @return void
     */
    public function reset()
    {
        $this->ensureInitialized();

        if (function_exists('curl_reset')) {
            curl_reset($this->ch);
            $this->applyDefaults();
        } else {
            // Manual reset for PHP < 5.5
            $this->close();
            $this->init(); // This calls applyDefaults() again
            return;
        }

        $this->errors = array();
    }

    /**
     * Execute cURL request
     *
     * @return mixed|false
     */
    public function exec()
    {
        $this->ensureInitialized();
        $this->errors = array(); // Always reset errors first

        $result = curl_exec($this->ch);

        $errno = curl_errno($this->ch);
        $error = curl_error($this->ch);

        if ($errno !== CURLE_OK) {
            $this->errors[] = array(
                'code' => $errno,
                'message' => $error,
            );
        }

        return $result;
    }

    /**
     * Close cURL session
     * 
     * @return void
     */
    public function close()
    {
        if ($this->isHandleValid()) {
            curl_close($this->ch);
            $this->ch = null;
        }
    }

    /**
     * Set a single cURL option
     *
     * @param int $option
     * @param mixed $value
     * @return bool
     */
    public function setOption($option, $value)
    {
        $this->ensureInitialized();
        $result = curl_setopt($this->ch, $option, $value);

        if ($result === false) {
            throw new InvalidArgumentException(sprintf('Failed to set cURL option %d', $option));
        }

        return $result;
    }

    /**
     * Set multiple cURL options
     *
     * @param array $options
     * @return bool
     */
    public function setOptions(array $options)
    {
        $this->ensureInitialized();
        $result = curl_setopt_array($this->ch, $options);

        if ($result === false) {
            throw new InvalidArgumentException('Failed to set one or more cURL options');
        }

        return $result;
    }

    /**
     * Get transfer info
     *
     * @param int $opt
     * @return mixed
     */
    public function getInfo($opt = 0)
    {
        $this->ensureInitialized();
        return curl_getinfo($this->ch, $opt);
    }

    /**
     * Get all transfer info
     *
     * @return array
     */
    public function getInfoAll()
    {
        $this->ensureInitialized();
        return curl_getinfo($this->ch);
    }

    /**
     * Get last error number
     *
     * @return int
     */
    public function getErrno()
    {
        return $this->isHandleValid() ? curl_errno($this->ch) : 0;
    }

    /**
     * Get last error message
     *
     * @return string
     */
    public function getError()
    {
        return $this->isHandleValid() ? curl_error($this->ch) : '';
    }

    /**
     * Get collected errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Check if last execution had errors
     *
     * @return bool
     */
    public function hasError()
    {
        return !empty($this->errors);
    }

    /**
     * Get raw cURL handle (advanced usage)
     *
     * @return mixed
     */
    public function getHandle()
    {
        $this->ensureInitialized();
        return $this->ch;
    }

    /**
     * Get cURL version information
     *
     * @return array
     */
    public function getVersion()
    {
        return curl_version();
    }

    /**
     * Apply safe default options
     * 
     * @return void
     */
    private function applyDefaults()
    {
        $this->setOptions(array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_AUTOREFERER    => true,
            CURLOPT_ENCODING       => '', // Accept all encodings
            CURLOPT_USERAGENT      => 'Krystal HTTP Client (PHP 5.6+)'
        ));
    }

    /**
     * Ensure cURL is initialized
     * 
     * @throws \RuntimeException if cURL session has not been initialized
     * @return void
     */
    private function ensureInitialized()
    {
        if (!$this->isHandleValid()) {
            throw new RuntimeException('cURL session has not been initialized');
        }
    }

    /**
     * Check handle validity across PHP versions
     *
     * @return bool
     */
    private function isHandleValid()
    {
        // Handle all PHP versions (5.6 to 8+)
        if ($this->ch === null) {
            return false;
        }

        // PHP 8.0+: CurlHandle object
        if (PHP_MAJOR_VERSION >= 8) {
            return $this->ch instanceof \CurlHandle;
        }

        // PHP 5.6-7.4: Resource
        return is_resource($this->ch);
    }
}

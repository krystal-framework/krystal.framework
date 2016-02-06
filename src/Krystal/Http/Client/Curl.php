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

use RuntimeException;

if (!function_exists('curl_init')) {
    throw new RuntimeException('To use cURL HTTP adapter, you must have curl extension installed');
}

final class Curl implements CurlInterface
{
    /**
     * Curl resource
     * 
     * @var resource
     */
    private $ch;

    /**
     * Curl errors regarding current session
     * 
     * @var array
     */
    private $errors = array();

    /**
     * State initialization
     * 
     * @param array $options
     * @return void
     */
    public function __construct(array $options = array())
    {
        if (!empty($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * Destructor.
     * Closes connection if opened 
     * 
     * @return void
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Closes cURL connection
     *
     * @return void
     */
    public function close()
    {
        if (is_resource($this->ch)) {
            curl_close($this->ch);
        }
    }

    /**
     * Inits the cURL
     * 
     * @param array $options
     * @return void
     */
    public function init(array $options = array())
    {
        $this->ch = curl_init();

        if (!empty($options)) {
            $this->setOptions($options);
        }
    }

    /**
     * Returns a clone 
     *
     * @return object
     */
    public function __clone()
    {
        return curl_copy_handle($this->ch);
    }

    /**
     * Return curl errors (if any)
     * 
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Perform a cURL session
     * This method should be called after initializing a cURL session  
     * and all the options for the session are set.
     * 
     * @return mixed, False on failure
     */
    public function exec()
    {
        $result = curl_exec($this->ch);

        if ($result === false) {
            $this->appendError();
            return false;
        } else {
            return $result;
        }
    }

    /**
     * Appends an error with its own code
     * 
     * @return void
     */
    private function appendError()
    {
        $this->errors[(string) curl_errno($this->ch)] = curl_error($this->ch);
    }

    /**
     * Set CURL options
     * 
     * @param array $options
     * @return boolean
     */
    public function setOptions(array $options)
    {
        return curl_setopt_array($this->ch, $options);
    }

    /**
     * Returns version info
     * 
     * @return array
     */
    public function getVersion()
    {
        return curl_version();
    }
}

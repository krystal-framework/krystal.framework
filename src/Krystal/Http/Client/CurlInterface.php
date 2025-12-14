<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\Client;

interface CurlInterface
{
    /**
     * Initialize cURL session
     *
     * @param array $options
     * @return void
     */
    public function init(array $options = array());

    /**
     * Reset cURL handle state
     * 
     * @return void
     */
    public function reset();

    /**
     * Execute cURL request
     *
     * @return mixed|false
     */
    public function exec();

    /**
     * Close cURL session
     * 
     * @return void
     */
    public function close();

    /**
     * Set a single cURL option
     *
     * @param int $option
     * @param mixed $value
     * @return bool
     */
    public function setOption($option, $value);

    /**
     * Set multiple cURL options
     *
     * @param array $options
     * @return bool
     */
    public function setOptions(array $options);

    /**
     * Get transfer info
     *
     * @param int $opt
     * @return mixed
     */
    public function getInfo($opt = 0);

    /**
     * Get all transfer info
     *
     * @return array
     */
    public function getInfoAll();

    /**
     * Get last error number
     *
     * @return int
     */
    public function getErrno();

    /**
     * Get last error message
     *
     * @return string
     */
    public function getError();

    /**
     * Get collected errors
     *
     * @return array
     */
    public function getErrors();

    /**
     * Check if last execution had errors
     *
     * @return bool
     */
    public function hasError();

    /**
     * Get raw cURL handle (advanced usage)
     *
     * @return mixed
     */
    public function getHandle();

    /**
     * Get cURL version information
     *
     * @return array
     */
    public function getVersion();
}

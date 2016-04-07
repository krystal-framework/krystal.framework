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


interface CurlInterface
{
    /**
     * Get information regarding a specific transfer
     * 
     * @param integer $opt
     * @return mixed
     */
    public function getInfo($opt = 0);

    /**
     * Closes cURL connection
     *
     * @return void
     */
    public function close();

    /**
     * Return last error messages like [Code => Text]
     * 
     * @return array
     */
    public function getErrors();

    /**
     * Sends a request
     * 
     * @return mixed, FALSE on failure
     */
    public function exec();

    /**
     * Set curl options
     * 
     * @param array $options
     */
    public function setOptions(array $options);

    /**
     * Returns cURL version
     * 
     * @return string
     */
    public function getVersion();
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\Response;

interface HttpResponseInterface
{
    /**
     * Returns header bag
     * 
     * @return \Krystal\Http\HeaderBagInterface
     */
    public function getHeaderBag();

    /**
     * Performs HTTP-digest authentication
     * 
     * @param string $login
     * @param string $password
     * @return boolean
     */
    public function authenticate($login, $password);

    /**
     * Forces to respond as JSON
     * 
     * @return \Krystal\Http\Response\HttpResponse
     */
    public function respondAsJson();

    /**
     * Forces to respond as XML
     * 
     * @return \Krystal\Http\Response\HttpResponse
     */
    public function respondAsXml();

    /**
     * Downloads a file
     * 
     * @param string $filename A path to the target file
     * @param string $alias Basename name can be optionally changed
     * @return void
     */
    public function download($filename, $alias = null);

    /**
     * Prepared and appends HTTP status message to the queue by associated code
     * 
     * @param integer $code
     * @return \Krystal\Http\Response\HttpResponse
     */
    public function setStatusCode($code);

    /**
     * Returns HTTP status code
     * 
     * @return integer
     */
    public function getStatusCode();

    /**
     * Redirects to given URL
     * 
     * @param string $url
     * @return void
     */
    public function redirect($url);

    /**
     * Enables internal HTTP cache mechanism
     * 
     * @param integer $timestamp Last modified timestamp
     * @param integer $ttl Time to live in seconds
     * @return \Krystal\Http\Response\HttpResponse
     */
    public function enableCache($timestamp, $ttl);

    /**
     * Generates and appends to the queue "Content-Type" header
     * 
     * @param string $type Content type
     * @return \Krystal\Http\Response\HttpResponse
     */
    public function setContentType($type);

    /**
     * Sets signature
     * 
     * @param string $signature
     * @return \Krystal\Http\Response\HttpResponse
     */
    public function setSignature($signature);

    /**
     * Forces to disable internal HTTP cache mechanism
     * 
     * @return \Krystal\Http\Response\HttpResponse
     */
    public function disableCache();

    /**
     * Is the HTTP response was defined as client error?
     *
     * @return boolean
     */
    public function isClientError();

    /**
     * Is the HTTP response was defined as forbidden?
     *
     * @return boolean
     */
    public function isForbidden();

    /**
     * Is the HTTP response was defined as informational?
     *
     * @return boolean
     */
    public function isInformational();

    /**
     * Is the HTTP response was defined as not found?
     *
     * @return boolean
     */
    public function isNotFound();

    /**
     * Is the HTTP response was defined as OK?
     *
     * @return boolean
     */
    public function isOk();

    /**
     * Is the HTTP response was defined as server error?
     *
     * @return boolean
     */
    public function isServerError();

    /**
     * Is the HTTP response was defined as redirect?
     *
     * @return boolean
     */
    public function isRedirect();

    /**
     * Is the HTTP response was defined as successful?
     *
     * @return boolean
     */
    public function isSuccess();

    /**
     * Sends the response
     * 
     * @param string $content Content to be sent
     * @return void
     */
    public function send($content);
}

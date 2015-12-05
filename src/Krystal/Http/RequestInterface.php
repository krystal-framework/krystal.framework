<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http;

interface RequestInterface
{
    /**
     * Checks whether requested either via POST or GET
     * 
     * @return boolean
     */
    public function isIntended();

    /**
     * Returns CSRF token if present in headers
     * 
     * @return string|boolean
     */
    public function getMetaCsrfToken();

    /**
     * Determines whether X-CSRF-TOKEN has been sent with a request
     * 
     * @return boolean
     */
    public function hasMetaCsrfToken();

    /**
     * Returns all request data
     * 
     * @param boolean $separate Whether to separate data from files into distinct resulting array keys
     * @return array
     */
    public function getAll($separate = true);

    /**
     * Whether files array was populated with data
     * 
     * @return boolean
     */
    public function hasFiles($name = null);

    /**
     * Return files
     * 
     * @return array
     */
    public function getFiles($name = null);

    /**
     * Returns a single file bag from uploaded collection
     *  
     * @param string $name Optional field name
     * @param string $index File key index. 0 - means return first
     * @param mixed $default Default value to be returned in case $index doesn't exist
     * @return array
     */
    public function getFile($name = null, $index = 0, $default = false);

    /**
     * Serializes the request string
     * 
     * @param array $data
     * @return string
     */
    public function serialize(array $data);

    /**
     * Converts serialized string we got from form
     * (or query string) into an associative array
     * 
     * @param string $string Serialized string
     * @return array on success, NULL on failure
     */
    public function unserialize($string);

    /**
     * Returns current URL request made from
     * 
     * @return string
     */
    public function getCurrentURL();

    /**
     * Returns client IP address
     * 
     * @param boolean $proxy Whether to rely on proxy
     * @return string
     */
    public function getClientIP($proxy = false);

    /**
     * Returns the document root directory 
     * under which the current script is executing, as defined in the server's configuration file.
     * 
     * @return string
     */
    public function getRootDir();

    /**
     * Returns current HTTP host
     * 
     * @return string
     */
    public function getHost();

    /**
     * Returns Server IP
     * 
     * @return string
     */
    public function getServerIP();

    /**
     * Returns request method, POST or GET
     * 
     * @return string
     */
    public function getMethod();

    /**
     * Returns all sub-domains
     * 
     * @return array
     */
    public function getSubdomains();

    /**
     * Returns base domain
     * 
     * @return string
     */
    public function getDomain();

    /**
     * Returns all available languages supported by browser
     * 
     * @return array
     */
    public function getLanguages();

    /**
     * Returns default language of current browser
     * 
     * @return string
     */
    public function getLanguage();

    /**
     * Returns timestamp of a request
     * 
     * @return string
     */
    public function getTimestamp();

    /**
     * Returns server port
     * 
     * @return integer
     */
    public function getServerPort();

    /**
     * Returns base URL
     * 
     * @return string
     */
    public function getBaseUrl();

    /**
     * Returns a remote port
     * 
     * @return integer
     */
    public function getRemotePort();

    /**
     * Whether request was redirected
     * 
     * @return boolean
     */
    public function isRedirected();

    /**
     * Returns script location
     * 
     * @return string
     */
    public function getScriptLocation();

    /**
     * Checks whether requested from secured connection
     * 
     * @return boolean
     */
    public function isSecure();

    /**
     * Returns protocol scheme
     * 
     * @return string
     */
    public function getScheme();

    /**
     * Returns current URI string
     * 
     * @return string
     */
    public function getURI();

    /**
     * Determines whether requested via AJAX
     * 
     * @return boolean
     */
    public function isXHR();

    /**
     * Determines whether request has been made from a flash script
     * 
     * @return boolean
     */
    public function isFlash();

    /**
     * Whether accessed from command line
     * 
     * @return boolean
     */
    public function isCLI();

    /**
     * Checks whether query has at least non-empty value
     * 
     * @param string $filter Optionally can be filtered by a group
     * @return boolean
     */
    public function hasQueryVals($filter = null);

    /**
     * Checks whether there's a key in namespaced query
     * 
     * @param string $ns Namespace (Group)
     * @param string $key
     * @return boolean
     */
    public function hasQueryNs($ns, $key);

    /**
     * Returns key's value from a namespace
     * 
     * @param string $ns Target namespace (Group)
     * @param string $key Target key
     * @param mixed $default Default value to be returned on failure
     * @return mixed
     */
    public function getQueryNs($ns, $key, $default);

    /**
     * Merges and returns current query data with defined data and returns as query string
     * 
     * @param string $ns Target namespace (Group)
     * @param array $data Data to be merged
     * @param boolean $mark Whether to prepend question mark
     * @return string
     */
    public function getWithNsQuery($ns, array $data, $mark = true);

    /**
     * Returns post parameter
     * 
     * @param string $key
     * @param mixed $default Default value to be returned on absence
     * @return string|array
     */
    public function getPost($key = null, $default = false);

    /**
     * Returns query parameter
     * 
     * @param string $key
     * @param mixed $default Default value to be returned on absence
     * @return string
     */
    public function getQuery($key = null, $default = false);

    /**
     * Determines whether request via POST method with Ajax-compliant headers
     * 
     * @return boolean
     */
    public function isAjaxPost();

    /**
     * Determines whether request via GET method with Ajax-compliant headers
     * 
     * @return boolean
     */
    public function isAjaxGet();

    /**
     * Whether requested via Ajax
     * 
     * @return boolean
     */
    public function isAjax();

    /**
     * Checks whether requested via POST method
     * 
     * @return boolean
     */
    public function isPost();

    /**
     * Checks whether requested via GET method
     * 
     * @return boolean 
     */
    public function isGet();

    /**
     * Whether requested via OPTIONS method
     * 
     * @return boolean
     */
    public function isOptions();

    /**
     * Whether requested via PROPFIND method
     * 
     * @return boolean
     */
    public function isPropFind();

    /**
     * Whether requested via HEAD method
     * 
     * @return boolean
     */
    public function isHead();

    /**
     * Whether requested via PUT method
     * 
     * @return boolean
     */
    public function isPut();

    /**
     * Whether requested via DELETE method
     * 
     * @return boolean
     */
    public function isDelete();

    /**
     * Whether requested via TRACE method
     * 
     * @return boolean
     */
    public function isTrace();

    /**
     * Whether requested via CONNECT method
     * 
     * @return boolean
     */
    public function isConnect();

    /**
     * Whether requested via PATCH method
     * 
     * @return boolean
     */
    public function isPatch();
}

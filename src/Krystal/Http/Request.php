<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http;

use Krystal\Http\FileTransfer\Input as FileInput;
use UnexpectedValueException;

final class Request implements RequestInterface
{
    /**
     * POST data
     * 
     * @var array
     */
    private $post = array();

    /**
     * GET data
     * 
     * @var array
     */
    private $query = array();

    /**
     * Server data
     * 
     * @var array
     */
    private $server = array();

    /**
     * A files super-global
     * 
     * @var array
     */
    private $files = array();

    /**
     * Cookie bag to manage cookies
     *  
     * @var \Krystal\Http\CookieBagInterface
     */
    private $cookieBag;

    /**
     * State initialization
     * 
     * @param array $query $_GET superglobal
     * @param array $post $_POST superglobal
     * @param \Krystal\Http\CookieBagInterface $cookieBag
     * @param array $server $_SERVER superglobal
     * @param array $files $_FILES superglobal
     * @return void
     */
    public function __construct(array $query, array $post, CookieBagInterface $cookieBag, array $server, array $files)
    {
        $this->query = $query;
        $this->post = $post;
        $this->cookieBag = $cookieBag;
        $this->server = $server;
        $this->files = $files;
    }

    /**
     * Run HTTP to HTTPs redirect
     * 
     * @return void
     */
    public function sslRedirect()
    {
        if (!$this->isSecure()) {
            $redirect = 'https://' . $this->server['HTTP_HOST'] . $this->server['REQUEST_URI'];

            header('HTTP/1.1 301 Moved Permanently');
            header('Location: ' . $redirect);
            exit();
        }
    }

    /**
     * Checks whether requires comes from local machine
     * 
     * @return boolean
     */
    public function isLocal()
    {
        return $this->server['SERVER_ADDR'] === $this->getClientIP();
    }

    /**
     * Checks whether a path looks as an URL
     * 
     * @param string $path
     * @return boolean
     */
    public function isUrlLike($path)
    {
        $path = trim($path);
        return substr($path, 0, 4) === 'http' || substr($path, 0, 2) === '//';
    }

    /**
     * Checks whether requested either via POST or GET
     * 
     * @return boolean
     */
    public function isIntended()
    {
        return $this->isGet() || $this->isPost();
    }

    /**
     * Returns CSRF token if present in headers
     * 
     * @return string|boolean
     */
    public function getMetaCsrfToken()
    {
        return $this->hasMetaCsrfToken() ? $this->server['HTTP_X_CSRF_TOKEN'] : false;
    }

    /**
     * Determines whether X-CSRF-TOKEN has been sent with a request
     * 
     * @return boolean
     */
    public function hasMetaCsrfToken()
    {
        return isset($this->server['HTTP_X_CSRF_TOKEN']);
    }

    /**
     * Return cookie bag
     * 
     * @return \Krystal\Http\CookieBag
     */
    public function getCookieBag()
    {
        return $this->cookieBag;
    }

    /**
     * Returns all request data
     * 
     * @param boolean $separate Whether to separate data from files into distinct resulting array keys
     * @return array
     */
    public function getAll($separate = true)
    {
        if ($this->isPost()) {
            $data = $this->getPost();
        }

        if ($this->isGet()) {
            $data = $this->getQuery();
        }

        // Append files also, if we have them
        if ($this->hasFiles()) {
            $files = $this->getFiles();
        } else {
            $files = array();
        }

        if ($separate === false) {
            return array_merge($data, $files);
        } else {
            $result = array();
            $result['data'] = $data;
            $result['files'] = !empty($files) ? $files : array();

            return $result;
        }
    }

    /**
     * Builds query string
     * 
     * @param array $params
     * @param boolean $includeCurrent Whether to include current data from query string
     * @return array
     */
    public function buildQuery(array $params, $includeCurrent = true)
    {
        if ($includeCurrent == true) {
            $params = array_replace_recursive($this->getQuery(), $params);
        }

        return $this->serialize($params);
    }

    /**
     * Parses raw JSON body received from POST request and returns it as array
     * 
     * @throws \UnexpectedValueException if no POST parameters or JSON string has syntax errors
     * @return array
     */
    public function getJsonBody()
    {
        $input = json_decode($this->getRawInput(), true);

        if (json_last_error()) {
            throw new UnexpectedValueException('JSON string has syntax errors or POST has no parameters');
        }

        return $input;
    }

    /**
     * Parses raw XML body received from POST request and returns it as array
     * 
     * @throws \UnexpectedValueException if no POST parameters or XML string has syntax errors
     * @return object
     */
    public function getXmlBody()
    {
        $body = simplexml_load_string($this->getRawInput());

        if ($body === false) {
            throw new UnexpectedValueException('XML string has syntax errors or POST has no parameters');
        }

        return $body;
    }

    /**
     * Returns raw HTTP POST input
     * 
     * @return string
     */
    public function getRawInput()
    {
        return file_get_contents('php://input');
    }

    /**
     * Return files, optionally the result-set can be filtered by field name
     * 
     * @param string $name Optional field name
     * @return array
     */
    public function getFiles($name = null)
    {
        return $this->getFilesbag()->getFiles($name);
    }

    /**
     * Returns a single file bag from uploaded collection
     *  
     * @param string $name Optional field name
     * @param string $index File key index. 0 - means return first
     * @param mixed $default Default value to be returned in case $index doesn't exist
     * @return array
     */
    public function getFile($name = null, $index = 0, $default = false)
    {
        $files = $this->getFiles($name);

        if (isset($files[$index])) {
            return $files[$index];
        } else {
            return $default;
        }
    }

    /**
     * Checks whether we at least one file
     * 
     * @param string $name Can be optionally filtered by a name
     * @return boolean Depending on success
     */
    public function hasFiles($name = null)
    {
        if (!empty($this->files)) {
            // if $name is null, then a global checking must be done
            return $this->getFilesbag()->hasFiles($name);
        }

        // By default
        return false;
    }

    /**
     * Returns files bag
     * For internal usage only
     * 
     * @return \Krystal\Http\FileTransfer\Input
     */
    private function getFilesbag()
    {
        static $filesBag = null;

        if (is_null($filesBag)) {
            $filesBag = new FileInput($this->files);
        }

        return $filesBag;
    }

    /**
     * Serializes the request string
     * 
     * @param array $data
     * @return string
     */
    public function serialize(array $data)
    {
        return http_build_query($data);
    }

    /**
     * Converts serialized string we got from form
     * (or query string) into an associative array
     * 
     * @param string $string Serialized string
     * @return array on success, NULL on failure
     */
    public function unserialize($string)
    {
        $result = array();
        parse_str($string, $result);

        return $result;
    }

    /**
     * Returns current URL request made from
     * 
     * @return string
     */
    public function getCurrentURL()
    {
        return $this->getBaseUrl() . $this->getURI();
    }

    /**
     * Returns client IP address
     * 
     * @param boolean $proxy Whether to rely on proxy
     * @return string
     */
    public function getClientIP($proxy = false)
    {
        if ($proxy !== false) {
            if (isset($this->server['HTTP_CLIENT_IP'])) {
                return $this->server['HTTP_CLIENT_IP'];
            }

            if (isset($this->server['HTTP_X_FORWARDED_FOR'])) {
                return $this->server['HTTP_X_FORWARDED_FOR'];
            }

        } else {
            return $this->server['REMOTE_ADDR'];
        }
    }

    /**
     * Returns server software
     * 
     * @return string
     */
    public function getServerSoftware()
    {
        return isset($this->server['SERVER_SOFTWARE']) ? $this->server['SERVER_SOFTWARE'] : false;
    }

    /**
     * Returns the document root directory 
     * 
     * @return string
     */
    public function getRootDir()
    {
        return $this->server['DOCUMENT_ROOT'];
    }

    /**
     * Returns current HTTP host
     * 
     * @return string
     */
    public function getHost()
    {
        return $this->server['HTTP_HOST'];
    }

    /**
     * Returns server IP
     * 
     * @return string
     */
    public function getServerIP()
    {
        return $this->server['SERVER_ADDR'];
    }

    /**
     * Returns request method, POST or GET
     * 
     * @return string
     */
    public function getMethod()
    {
        return strtoupper($this->server['REQUEST_METHOD']);
    }

    /**
     * Returns all sub-domains
     * 
     * @return array
     */
    public function getSubdomains()
    {
        $current = $this->server['HTTP_HOST'];
        $parts = explode('.', $current);

        // two steps back
        array_pop($parts);
        array_pop($parts);

        return $parts;
    }

    /**
     * Returns base domain
     * 
     * @return string
     */
    public function getDomain()
    {
        $current = $this->server['HTTP_HOST'];
        $parts = explode('.', $current);

        $zone = array_pop($parts);
        $provider = array_pop($parts);

        return $provider . '.' . $zone;
    }

    /**
     * Returns all available languages supported by browser
     * 
     * @return array
     */
    public function getLanguages()
    {
        $source = $this->server['HTTP_ACCEPT_LANGUAGE'];

        if (strpos($source, ',') !== false) {
            $langs = explode(',', $source);
            return $langs;
        } else {
            return array($source);
        }
    }

    /**
     * Returns default language of current browser
     * 
     * @return string
     */
    public function getLanguage()
    {
        return substr($this->server['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    }

    /**
     * Returns timestamp of a request
     * 
     * @return string
     */
    public function getTimestamp()
    {
        return $this->server['REQUEST_TIME'];
    }

    /**
     * Returns server port
     * 
     * @return integer
     */
    public function getServerPort()
    {
        return $this->server['SERVER_PORT'];
    }

    /**
     * Returns base URL
     * 
     * @return string
     */
    public function getBaseUrl()
    {
        return sprintf("%s://%s", $this->getScheme(), $this->getHost());
    }

    /**
     * Returns a remote port
     * 
     * @return integer
     */
    public function getRemotePort()
    {
        return $this->server['REMOTE_PORT'];
    }

    /**
     * Tests whether request was redirected
     * 
     * @return boolean
     */
    public function isRedirected()
    {
        return isset($this->server['REDIRECT_STATUS']) && $this->server['REDIRECT_STATUS'] == 200;
    }

    /**
     * Returns script location
     * 
     * @return string
     */
    public function getScriptLocation()
    {
        return $this->server['SCRIPT_FILENAME'];
    }

    /**
     * Checks whether requested from secured connection
     * 
     * @return boolean
     */
    public function isSecure()
    {
        return (
            (!empty($this->server['HTTPS']) && $this->server['HTTPS'] != 'off') || 
            (!empty($this->server['HTTP_HTTPS']) && $this->server['HTTP_HTTPS'] != 'off') || 
            $this->server['REQUEST_SCHEME'] == 'https' || $this->server['SERVER_PORT'] == 443
        );
    }

    /**
     * Returns protocol scheme
     * 
     * @return string
     */
    public function getScheme()
    {
        return $this->isSecure() ? 'https' : 'http';
    }

    /**
     * Returns current URI string
     * 
     * @return string
     */
    public function getURI()
    {
        return $this->server['REQUEST_URI'];
    }

    /**
     * Determines whether requested via AJAX
     * 
     * @return boolean
     */
    public function isXHR()
    {
        return isset($this->server['HTTP_X_REQUESTED_WITH']);
    }

    /**
     * Determines whether request has been made from a flash script
     * 
     * @return boolean
     */
    public function isFlash()
    {
        return stripos($this->server['USER_AGENT'], 'Shockwave Flash') !== false;
    }

    /**
     * Determines whether accessed from the command line
     * 
     * @return boolean
     */
    public function isCLI()
    {
        return php_sapi_name() === 'cli';
    }

    /**
     * Checks whether query has at least non-empty value
     * 
     * @param string $filter Optionally can be filtered by a group
     * @return boolean
     */
    public function hasQueryVals($filter = null)
    {
        // This should only work for GET request
        if (!$this->isGet()) {
            return false;
        }

        if ($filter !== null && $this->hasQuery($filter)) {
            $data = $this->getQuery($filter);
        } else {
            $data = $this->getQuery();
        }

        foreach ($data as $key => $value) {
            // If it's positive-like, then stop returning true
            if ($value == '0' || $value) {
                return true;
            }
        }

        // By default
        return false;
    }

    /**
     * Checks whether there's a key in namespaced query
     * 
     * @param string $ns Namespace (Group)
     * @param string $key
     * @return boolean
     */
    public function hasQueryNs($ns, $key)
    {
        $data = $this->getQuery($ns);

        // If there's no such key, then $data isn't an array, so just make sure
        if (is_array($data)) {
            return array_key_exists($key, $data);
        } else {
            // If $data isn't an array, then there's no such key
            return false;
        }
    }

    /**
     * Returns key's value from a namespace
     * 
     * @param string $ns Target namespace (Group)
     * @param string $key Target key
     * @param mixed $default Default value to be returned on failure
     * @return mixed
     */
    public function getQueryNs($ns, $key, $default)
    {
        if ($this->hasQueryNs($ns, $key)) {
            $data = $this->getQuery($ns);

            if (isset($data[$key])) {
                return $data[$key];
            }
        }

        return $default;
    }

    /**
     * Merges and returns current query data with defined data and returns as query string
     * 
     * @param string $ns Target namespace (Group)
     * @param array $data Data to be merged
     * @param boolean $mark Whether to prepend question mark
     * @return string
     */
    public function getWithNsQuery($ns, array $data, $mark = true)
    {
        if ($this->hasQuery($ns)) {
            $query = $this->getQuery($ns);
            $url = null;

            if ($mark === true) {
                $url = '?';
            }

            $url .= http_build_query(array($ns => array_merge($query, $data)));
            $url = str_replace('%25s', '%s', $url);

            return $url;
        } else {
            return null;
        }
    }

    /**
     * Returns current query with merged data
     * 
     * @param array $data Data to be merged with current query
     * @param boolean $mark Whether to prepend question mark
     * @return string
     */
    public function getWithQuery(array $data, $mark = true)
    {
        if ($this->hasQuery()) {
            $url = null;

            if ($mark === true) {
                $url = '?';
            }

            $url .= http_build_query(array_merge($this->getQuery(), $data));
            $url = str_replace('%25s', '%s', $url);

            return $url;

        } else {
            return null;
        }
    }

    /**
     * Checks whether query has a parameter or its empty
     * 
     * @param string $key, [...]
     * @return boolean
     */
    public function hasQuery()
    {
        if (func_num_args() == 0) {
            return !empty($this->query);
        }

        foreach (func_get_args() as $key) {
            if (!$this->hasParam($this->query, $key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks whether post parameter exists or the whole array isn't empty
     * 
     * @param string $key, [...]
     * @return boolean
     */
    public function hasPost()
    {
        if (func_num_args() == 0) {
            return !empty($this->post);
        }

        foreach (func_get_args() as $key) {
            if (!$this->hasParam($this->post, $key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks whether we have a parameter in array
     * 
     * @param array $array Target array we're dealing with
     * @param string $key
     * @return boolean
     */
    private function hasParam(array $array, $key = null)
    {
        if ($key !== null) {
            return array_key_exists($key, $array);
        } else {
            return !empty($array);
        }
    }

    /**
     * Returns post parameter
     * 
     * @param string $key
     * @param mixed $default Default value to be returned on absence
     * @return string|array
     */
    public function getPost($key = null, $default = false)
    {
        if ($key !== null) {
            if (array_key_exists($key, $this->post)) {
                return $this->post[$key];
            } else {
                return $default;
            }

        } else {
            return $this->post;
        }
    }

    /**
     * Returns query parameter
     * 
     * @param string $key
     * @param mixed $default Default value to be returned on absence
     * @return string
     */
    public function getQuery($key = null, $default = false)
    {
        if ($key !== null) {
            if (array_key_exists($key, $this->query)) {
                return $this->query[$key];
            } else {
                return $default;
            }
        } else {
            return $this->query;
        }
    }

    /**
     * Determines whether request via POST method with Ajax-compliant headers
     * 
     * @return boolean
     */
    public function isAjaxPost()
    {
        return $this->isPost() && $this->isAjax();
    }

    /**
     * Determines whether request via GET method with Ajax-compliant headers
     * 
     * @return boolean
     */
    public function isAjaxGet()
    {
        return $this->isGet() && $this->isAjax();
    }

    /**
     * Whether requested via Ajax
     * 
     * @return boolean
     */
    public function isAjax()
    {
        return $this->isXhr();
    }

    /**
     * Checks whether requested via POST method
     * 
     * @return boolean
     */
    public function isPost()
    {
        return $this->getMethod() === 'POST';
    }

    /**
     * Checks whether requested via GET method
     * 
     * @return boolean 
     */
    public function isGet()
    {
        return $this->getMethod() === 'GET';
    }

    /**
     * Whether requested via OPTIONS method
     * 
     * @return boolean
     */
    public function isOptions()
    {
        return $this->getMethod() === 'OPTIONS';
    }

    /**
     * Whether requested via PROPFIND method
     * 
     * @return boolean
     */
    public function isPropFind()
    {
        return $this->getMethod() === 'PROPFIND';
    }

    /**
     * Whether requested via HEAD method
     * 
     * @return boolean
     */
    public function isHead()
    {
        return $this->getMethod() === 'HEAD';
    }
    
    /**
     * Whether requested via PUT method
     * 
     * @return boolean
     */
    public function isPut()
    {
        return $this->getMethod() === 'PUT';
    }
    
    /**
     * Whether requested via DELETE method
     * 
     * @return boolean
     */
    public function isDelete()
    {
        return $this->getMethod() === 'DELETE';
    }
    
    /**
     * Whether requested via TRACE method
     * 
     * @return boolean
     */
    public function isTrace()
    {
        return $this->getMethod() === 'TRACE';
    }

    /**
     * Whether requested via CONNECT method
     * 
     * @return boolean
     */
    public function isConnect()
    {
        return $this->getMethod() === 'CONNECT';
    }

    /**
     * Whether requested via PATCH method
     * 
     * @return boolean
     */
    public function isPatch()
    {
        return $this->getMethod() === 'PATCH';
    }
}

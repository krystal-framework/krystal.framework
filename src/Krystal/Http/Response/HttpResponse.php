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

use Krystal\Http\HeaderBagInterface;

final class HttpResponse implements HttpResponseInterface
{
    /**
     * Header bag that manage headers
     * 
     * @var \Krystal\Http\HeaderBagInterface
     */
    private $headerBag;

    /**
     * HTTP Protocol version
     * 
     * @var string
     */
    private $version;

    /**
     * Charset for output
     * 
     * @var string
     */
    private $charset;

    /**
     * HTTP Status code to be sent
     * Success by default
     * 
     * @var integer
     */
    private $statusCode = 200;

    /**
     * State initialization
     * 
     * @param \Krystal\Http\HeaderBagInterface $headerBag
     * @param string $version
     * @param string $charset
     * @return void
     */
    public function __construct(HeaderBagInterface $headerBag, $version = '1.1', $charset = 'UTF-8')
    {
        $this->headerBag = $headerBag;
        $this->version = $version;
        $this->charset = $charset;
    }

    /**
     * Returns header bag
     * 
     * @return \Krystal\Http\HeaderBagInterface
     */
    public function getHeaderBag()
    {
        return $this->headerBag;
    }

    /**
     * Send HTTP headers that indicate that access isn't allowed
     * 
     * @return void
     */
    private function forbid()
    {
        // Configure failure headers
        $this->headerBag->clear()
                        ->appendPair('WWW-Authenticate', 'Basic realm="Private Area"')
                        ->append(sprintf('HTTP/%s 401 Unauthorized', $this->version));
    }

    /**
     * Performs HTTP-digest authentication
     * 
     * @param string $login
     * @param string $password
     * @return boolean
     */
    public function authenticate($login, $password)
    {
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            $this->forbid();
            return false;
        } else {
            if (($_SERVER['PHP_AUTH_USER'] == $login) && ($_SERVER['PHP_AUTH_PW'] == $password)) {
                return true;
            } else {
                $this->forbid();
                return false;
            }
        }
    }

    /**
     * Forces to respond as JSON
     * 
     * @return \Krystal\Http\Response\HttpResponse
     */
    public function respondAsJson()
    {
        $this->getHeaderBag()
             ->appendPair('Content-type', 'application/json');

        return $this;
    }

    /**
     * Forces to respond as XML
     * 
     * @return \Krystal\Http\Response\HttpResponse
     */
    public function respondAsXml()
    {
        $this->getHeaderBag()
             ->appendPair('Content-type', sprintf('text/xml; charset=%s', $this->charset));

        return $this;
    }

    /**
     * Downloads a file
     * 
     * @param string $filename A path to the target file
     * @param string $alias Basename name can be optionally changed
     * @return void
     */
    public function download($filename, $alias = null)
    {
        $fileDowloader = new FileDownloader($this->headerBag);
        $fileDowloader->download($filename, $alias);

        // Terminate the script with success
        exit(1);
    }

    /**
     * Prepared and appends HTTP status message to the queue by associated code
     * 
     * @param integer $code
     * @return \Krystal\Http\Response\HttpResponse
     */
    public function setStatusCode($code)
    {
        $this->headerBag->setStatusCode($code);
        $this->statusCode = $code;

        return $this;
    }

    /**
     * Returns HTTP status code
     * 
     * @return integer
     */
    public function getStatusCode()
    {
        return (int) $this->statusCode;
    }

    /**
     * Redirects to given URL
     * 
     * @param string $url
     * @return void
     */
    public function redirect($url)
    {
        $this->headerBag->set(sprintf('Location: %s', $url))
                        ->send();
        exit();
    }

    /**
     * Redirects to home page
     * 
     * @return void
     */
    public function redirectToHome()
    {
        $this->redirect('/');
    }

    /**
     * Enables internal HTTP cache mechanism
     * 
     * @param integer $timestamp Last modified timestamp
     * @param integer $ttl Time to live in seconds
     * @return \Krystal\Http\Response\HttpResponse
     */
    public function enableCache($timestamp, $ttl)
    {
        // HttpCache alters HeaderBag's state internally
        $handler = new HttpCache($this->headerBag);
        $handler->configure($timestamp, $ttl);

        return $this;
    }

    /**
     * Forces to disable internal HTTP cache mechanism
     * 
     * @return \Krystal\Http\Response\HttpResponse
     */
    public function disableCache()
    {
        $handler = new HttpNoCache($this->headerBag);
        $handler->configure();

        return $this;
    }

    /**
     * Generates and appends to the queue "Content-Type" header
     * 
     * @param string $type Content type
     * @return \Krystal\Http\Response\HttpResponse
     */
    public function setContentType($type)
    {
        $this->headerBag->appendPair('Content-Type', sprintf('%s;charset=%s', $type, $this->charset));
        return $this;
    }

    /**
     * Sets signature
     * 
     * @param string $signature
     * @return \Krystal\Http\Response\HttpResponse
     */
    public function setSignature($signature)
    {
        $this->headerBag->appendPair('X-Powered-By', $signature);
        return $this;
    }

    /**
     * Is the HTTP response was defined as client error?
     *
     * @return boolean
     */
    public function isClientError()
    {
        $code = $this->getStatusCode();
        return ($code < 500 && $code >= 400);
	}

    /**
     * Is the HTTP response was defined as forbidden?
     *
     * @return boolean
     */
    public function isForbidden()
    {
        return (403 === $this->getStatusCode());
    }

    /**
     * Is the HTTP response was defined as informational?
     *
     * @return boolean
     */
    public function isInformational()
    {
        $code = $this->getStatusCode();
        return ($code >= 100 && $code < 200);
    }

    /**
     * Is the HTTP response was defined as not found?
     *
     * @return boolean
     */
    public function isNotFound()
    {
        return (404 === $this->getStatusCode());
    }

    /**
     * Is the HTTP response was defined as OK?
     *
     * @return boolean
     */
    public function isOk()
    {
        return (200 === $this->getStatusCode());
    }

    /**
     * Is the HTTP response was defined as server error?
     *
     * @return boolean
     */
    public function isServerError()
    {
        $code = $this->getStatusCode();
        return (500 <= $code && 600 > $code);
    }

    /**
     * Is the HTTP response was defined as redirect?
     *
     * @return boolean
     */
    public function isRedirect()
    {
        $code = $this->getStatusCode();
        return (300 <= $code && 400 > $code);
    }

    /**
     * Is the HTTP response was defined as successful?
     *
     * @return boolean
     */
    public function isSuccess()
    {
        $code = $this->getStatusCode();
        return (200 <= $code && 300 > $code);
    }

    /**
     * Sends the response
     * 
     * @param string $content Content to be sent
     * @return void
     */
    public function send($content)
    {
        // Send all attached headers
        $this->headerBag->send();

        echo $content;

        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
    }
}
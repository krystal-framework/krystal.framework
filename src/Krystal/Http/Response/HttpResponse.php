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
     * State initialization
     * 
     * @param \Krystal\Http\HeaderBagInterface $headerBag
     * @return void
     */
    public function __construct(HeaderBagInterface $headerBag)
    {
        $this->headerBag = $headerBag;
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
        return $this;
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
     * @param string $charset
     * @return \Krystal\Http\Response\HttpResponse
     */
    public function setContentType($type, $charset)
    {
        $this->headerBag->appendPair('Content-Type', sprintf('%s;charset=%s', $type, $charset));
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
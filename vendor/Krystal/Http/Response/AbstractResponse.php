<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Http\Response;

/**
 * @todo Refactor this
 */
abstract class AbstractResponse
{
	/**
	 * Default mime-type for output
	 * 
	 * @var string
	 */
	private $type = 'text/html';
	
	/**
	 * Default charset
	 * 
	 * @var string
	 */
	private $charset = 'UTF-8';
	
	/**
	 * Whether it should cache output or not
	 * 
	 * @var boolean
	 */
	private $cache = false;
	
	/**
	 * Additional headers
	 * 
	 * @var array
	 */
	private $headers = array();
	
	/**
	 * X-Powered-By Signature
	 * 
	 * @var string
	 */
	private $signature = '';
	
	/**
	 * @var string
	 */
	private $version = '1.1';
	
	/**
	 * @var string
	 */
	private $content = null;
	
	/**
	 * Response statuses
	 * Code => Description pair
	 * 
	 * @var array
	 */
	private $statuses = array(
		
		100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
		418 => 'I am a teapot',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported'
	);

	/**
	 * Constructor
	 * 
	 * @param string $content The response content
	 * @param integer $status The status
	 * @param array	$headers $headers Additional headers
	 * @return void
	 */
	public function __construct($content = null, $status = 200, array $headers = array())
	{
		if (!is_null($content)) {
			$this->setContent($content);
		}
		
		$this->setStatus($status);
		
		if (!empty($headers)) {
			$this->addHeaders($headers);
		}
	}

	/**
	 * @return void
	 */
	private function cleanHeaders()
	{
		$this->headers = array();
	}
	
	/**
	 * Redirects to given url
	 * 
	 * @param string $url
	 * @return void
	 */
	public function redirect($url)
	{
		header(sprintf('Location: %s', $url));
		exit();
	}

	/**
	 * @param string $time
	 */
	public function enableCache($time = '+1 day')
	{
		array_push($this->options['headers'], array(
			'Date'			=> gmdate("D, j M Y G:i:s ", time()) . 'GMT',
			'Last-Modified' => gmdate("D, j M Y G:i:s ", $since) . 'GMT',
			'Expires'		=> gmdate("D, j M Y H:i:s", $time) . " GMT",
			'Cache-Control' => 'public, max-age=' . ($time - time()),
			'Pragma'		=> 'cache'
		));
	}

	/**
	 * Sets content type
	 * 
	 * @param	string $type
	 * @return	void
	 */
	public function setType($type)
	{
		$this->addHeaders(array('Content-Type' => sprintf('%s;charset=%s', $type, $this->getCharset())));
		$this->type = $type;
	}
	
	/**
	 * Sets charset option
	 * 
	 * @param string $charset
	 * @return object $this
	 */
	public function setCharset($charset)
	{
		$this->charset = $charset;
		return $this;
	}
	
	/**
	 * Returns response charset
	 * 
	 * @return string
	 */
	public function getCharset()
	{
		return $this->charset;
	}
	
	/**
	 * Sets signature
	 * 
	 * @param string $signature
	 * @return object $this
	 */
	public function setSignature($signature)
	{
		$this->addHeaders(array('X-Powered-By' => $signature));
		$this->options['signature'] = $signature;
		
		return $this;
	}
	
	/**
	 * Returns X-Powered-By Signature
	 * 
	 * @return string
	 */
	public function getSignature()
	{
		return $this->options['signature'];
	}
	
	/**
	 * Return status codes
	 * 
	 * @return array
	 */
	public function getStatuses()
	{
		return array_flip($this->statuses);
	}
	
	/**
	 * Whether to cache output
	 * Default is TRUE
	 * 
	 * @param boolean $state
	 * @return object $this
	 */
	public function isCached($state)
	{
		if ($state === FALSE) {
			
			// Add additional no-cache headers then
			$this->setHeaders(array(
				
				'Last-Modified'	=>	gmdate('D, d M Y H:i:s', strtotime('-1 day')) . ' GMT',
				'Cache-Control'	=>	'no-store, no-cache, must-revalidate',
				'Expires'		=>	'0',
				'Pragma'		=>	'no-cache'
			));
			
		} else {
			
			// $state is TRUE here
		}
		
		$this->cache = $state;
		
		return $this;
	}
	
	/**
	 * Adds additional headers
	 * 
	 * @param	array
	 * @return	object
	 */
	public function addHeaders(array $headers)
	{
		foreach ($headers as $key => $value) {
			array_push($this->headers, sprintf('%s : $s', $key, $value));
		}
	}
	
	/**
	 * Return additional header
	 * 
	 * @return array
	 */
	public function getHeaders()
	{
		return $this->headers;
	}
	
	/**
	 * Send additional headers
	 * 
	 * @return void
	 */
	private function sendHeaders()
	{
		if (headers_sent()) {
			return;
		}
		
		foreach ($this->getHeaders() as $header) {
			header($header);
		}
	}
	
	/**
	 * Returns body content
	 * 
	 * @return string
	 */
	private function getContent()
	{
		return $this->content;
	}
	
	/**
	 * Assigns body content
	 * 
	 * @param	string $content
	 * @return	void
	 */
	private function setContent($content)
	{
		$this->content = $content;
	}
	
	/**
	 * Assigns response status
	 * 
	 * @param	integer $status
	 * @return	object $this
	 */
	private function setStatus($status)
	{
		$this->addHeaders(array('HTTP/1.1'));
		$this->status = $status;
		
		return $this;
	}
	
	/**
	 * Returns response status
	 * 
	 * @return integer
	 */
	private function getStatus()
	{
		return $this->status;
	}
	
	/**
	 * Sends body content
	 * 
	 * @return void
	 */
	private function sendContent()
	{
		print $this->getContent();
	}

	/**
	 *
	 * @return void
	 */
	public function setNotFound()
	{
		// For fast-cgi
		//header("Status: 404 Not Found");
		
		header("Status: 404 Not Found");
	}

	/**
	 * Send final respond
	 * 
	 * @param string $content Content to be sent
	 * @return void
	 */
	public function send($content = null)
	{
		$this->sendHeaders();
		
		if (!is_null($content)) {
			$this->setContent($content);
		}

		$this->sendContent();

		if (function_exists('fastcgi_finish_request')) {
			fastcgi_finish_request();
		}
	}
}
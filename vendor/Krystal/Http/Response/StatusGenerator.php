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

use UnexpectedValueException;

final class StatusGenerator implements StatusGeneratorInterface
{
	/**
	 * HTTP protocol version
	 * 
	 * @var string
	 */
	private $version;

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
	 * State initialization
	 * 
	 * @param string $version HTTP protocol version
	 * @throws \UnexpectedValueException If unsupported HTTP protocol version supplied
	 * @return void
	 */
	public function __construct($version = '1.1')
	{
		// Make sure only supported version can be set
		if (in_array($version, array('1.1', '1.0'))) {
			$this->version = $version;
		} else {
			throw new UnexpectedValueException(sprintf('Unknown HTTP protocol version supplied "%s"', $version));
		}
	}

	/**
	 * Checks whether status code is valid
	 * 
	 * @param string $code
	 * @return boolean
	 */
	public function isValid($code)
	{
		return isset($this->statuses[(int) $code]);
	}

	/**
	 * Returns description by its associated code
	 * 
	 * @param integer $code Status code
	 * @return string
	 */
	public function getDescriptionByStatusCode($code)
	{
		// Make sure the expected type supplied
		$code = (int) $code;
		return $this->statuses[$code];
	}

	/**
	 * Generates status header by associated code
	 * 
	 * @param integer $code
	 * @return string|boolean
	 */
	public function generate($code)
	{
		if ($this->isValid($code)) {
			return sprintf('HTTP/%s %s %s', $this->version, $code, $this->getDescriptionByStatusCode($code));
		} else {
			return false;
		}
	}
}

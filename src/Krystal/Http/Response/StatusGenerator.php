<?php

/**
 * This file is part of the Krystal Framework
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
        // Basic informational
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        // Success
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-status',
        208 => 'Already Reported',
        // Redirection
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        // Client errors
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        // Server errors
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        511 => 'Network Authentication Required'
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
     * @throws \OutOfRangeException If the supplied code is out of range
     * @return string
     */
    public function getDescriptionByStatusCode($code)
    {
        // Make sure the expected type supplied
        $code = (int) $code;

        if (isset($this->statuses[$code])) {
            return $this->statuses[$code];
        } else {
            throw new OutOfRangeException(
                sprintf('The status code "%s" is out of allowed range', $code)
            );
        }
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

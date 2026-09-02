<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Debug\Exception;

use Krystal\Serializer\JsonSerializer;

final class Handler implements ExceptionHandlerInterface
{
    /**
     * Template file to be used when rendering exception
     * 
     * @var string
     */
    private $templateFile;

    /**
     * Server request parameters
     * 
     * @var array
     */
    private $server;

    /**
     * State initialization
     * 
     * @param array $server
     * @param string $templateFile
     * @return void
     */
    public function __construct(array $server, $templateFile = null)
    {
        $this->server = $server;

        if ($templateFile == null) {
            $templateFile = __DIR__ . '/template.phtml';
        }

        $this->templateFile = $templateFile;
    }

    /**
     * Check if the incoming request expects a JSON/API response
     * 
     * @return bool
     */
    private function isApiRequest()
    {
        // 1. Explicit JSON Accept header
        $accept = $this->server['HTTP_ACCEPT'] ?? '';
        if (strpos($accept, 'application/json') !== false || strpos($accept, 'text/json') !== false) {
            return true;
        }

        // 2. Incoming request payload is JSON
        $contentType = $this->server['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') !== false) {
            return true;
        }

        // 3. Standard AJAX header (Axios, jQuery)
        $requestedWith = $this->server['HTTP_X_REQUESTED_WITH'] ?? '';
        if (strtolower($requestedWith) === 'xmlhttprequest') {
            return true;
        }

        // 4. Modern Fetch / CORS requests (covers multipart/form-data sent via JS)
        $fetchMode = $this->server['HTTP_SEC_FETCH_MODE'] ?? '';
        if ($fetchMode === 'cors' || $fetchMode === 'same-origin') {
            $fetchDest = $this->server['HTTP_SEC_FETCH_DEST'] ?? '';
            if ($fetchDest === 'empty') { // 'empty' means fetch() or XHR call
                return true;
            }
        }

        return false;
    }

    /**
     * Custom exception handler
     * 
     * @param \Exception|\Throwable $exception
     * @return void
     */
    public function handle($exception)
    {
        if (PHP_SAPI === 'cli') {
            $output = sprintf(
                "\n[ERROR] Uncaught exception '%s' with message '%s'\nfile: %s:%d\n\n",
                get_class($exception),
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            );

            file_put_contents('php://stderr', $output);
            return;
        }

        // Handle all API, fetch, and frontend asynchronous requests automatically
        if ($this->isApiRequest()) {
            if (!headers_sent()) {
                http_response_code(500);
                header('Content-Type: application/json; charset=UTF-8');
            }

            $serializer = new JsonSerializer();

            echo $serializer->serialize([
                'success' => false,
                'error' => [
                    'message' => $exception->getMessage(),
                    'file'    => $exception->getFile(),
                    'line'    => $exception->getLine(),
                    'class'   => get_class($exception)
                ]
            ]);

            return;
        }

        $file = $exception->getFile();
        $line = $exception->getLine();
        $message = $exception->getMessage();
        $trace = $exception->getTrace();

        // Reverse and reset default order
        $trace = array_reverse($trace);

        // The name of thrown exception
        $class = get_class($exception);

        // Above variables will be available in the template
        require($this->templateFile);
    }

    /**
     * Registers custom exception handler
     * 
     * @return callable|null
     */
    public function register()
    {
        return set_exception_handler([$this, 'handle']);
    }
}
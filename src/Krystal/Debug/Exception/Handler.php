<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Debug\Exception;

final class Handler implements ExceptionHandlerInterface
{
    /**
     * Template file to be used when rendering exception
     * 
     * @var string
     */
    private $templateFile;

    /**
     * State initialization
     * 
     * @param string $templateFile
     * @return void
     */
    public function __construct($templateFile = null)
    {
        if ($templateFile == null) {
            $templateFile = __DIR__ . '/template.phtml';
        }

        $this->templateFile = $templateFile;
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
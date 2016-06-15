<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
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
     * @param \Exception $exception
     * @return void
     */
    public function handle($exception)
    {
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
     * @return void
     */
    public function register()
    {
        return set_exception_handler(array($this, 'handle'));
    }
}

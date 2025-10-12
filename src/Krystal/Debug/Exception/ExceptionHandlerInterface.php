<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Debug\Exception;

interface ExceptionHandlerInterface
{
    /**
     * Custom exception handler
     * 
     * @param \Exception $exception
     * @return void
     */
    public function handle($exception);

    /**
     * Registers custom exception handler
     * 
     * @return void
     */
    public function register();
}

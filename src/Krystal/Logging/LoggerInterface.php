<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Logging;

interface LoggerInterface
{
    /**
     * System is unusable.
     * 
     * @param string|\Stringable $message
     * @param array $context
     * @return void
     */
    public function emergency($message, array $context = []);

    /**
     * Action must be taken immediately.
     * 
     * @param string|\Stringable $message
     * @param array $context
     * @return void
     */
    public function alert($message, array $context = []);

    /**
     * Critical conditions.
     * 
     * @param string|\Stringable $message
     * @param array $context
     * @return void
     */
    public function critical($message, array $context = []);

    /**
     * Runtime errors that do not require immediate action but should typically be logged and monitored.
     * 
     * @param string|\Stringable $message
     * @param array $context
     * @return void
     */
    public function error($message, array $context = []);

    /**
     * Exceptional occurrences that are not errors.
     * 
     * @param string|\Stringable $message
     * @param array $context
     * @return void
     */
    public function warning($message, array $context = []);

    /**
     * Normal but significant events.
     * 
     * @param string|\Stringable $message
     * @param array $context
     * @return void
     */
    public function notice($message, array $context = []);

    /**
     * Interesting events.
     * 
     * @param string|\Stringable $message
     * @param array $context
     * @return void
     */
    public function info($message, array $context = []);

    /**
     * Detailed debug information.
     * 
     * @param string|\Stringable $message
     * @param array $context
     * @return void
     */
    public function debug($message, array $context = []);

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string|\Stringable $message
     * @param array $context
     * @return void
     */
    public function log($level, $message, array $context = []);
}

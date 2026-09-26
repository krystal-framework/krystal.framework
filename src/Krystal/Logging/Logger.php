<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Logging;

use Krystal\Logging\Adapter\LogWriterInterface;

/**
 * Main logger class that manages multiple writers (adapters).
 */
final class Logger implements LoggerInterface
{
    /**
     * A collection of log writers (adapters)
     * 
     * @var \Krystal\Logging\Adapter\LogWriterInterface[]
     */
    private $writers = [];

    /**
     * Adds a writer (adapter) to the logger.
     * 
     * @param \Krystal\Logging\Adapter\LogWriterInterface $writer
     * @return void
     */
    public function addWriter(LogWriterInterface $writer)
    {
        $this->writers[] = $writer;
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string|\Stringable $message
     * @param array $context
     * @return void
     */
    public function log($level, $message, array $context = [])
    {
        foreach ($this->writers as $writer) {
            $writer->write($level, (string) $message, $context);
        }
    }

    /**
     * System is unusable.
     * 
     * @param string|\Stringable $message
     * @param array $context
     * @return void
     */
    public function emergency($message, array $context = [])
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * Action must be taken immediately.
     * 
     * @param string|\Stringable $message
     * @param array $context
     * @return void
     */
    public function alert($message, array $context = [])
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * Critical conditions.
     * 
     * @param string|\Stringable $message
     * @param array $context
     * @return void
     */
    public function critical($message, array $context = [])
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically be logged and monitored.
     * 
     * @param string|\Stringable $message
     * @param array $context
     * @return void
     */
    public function error($message, array $context = [])
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     * 
     * @param string|\Stringable $message
     * @param array $context
     * @return void
     */
    public function warning($message, array $context = [])
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * Normal but significant events.
     * 
     * @param string|\Stringable $message
     * @param array $context
     * @return void
     */
    public function notice($message, array $context = [])
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * Interesting events.
     * 
     * @param string|\Stringable $message
     * @param array $context
     * @return void
     */
    public function info($message, array $context = [])
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * Detailed debug information.
     * 
     * @param string|\Stringable $message
     * @param array $context
     * @return void
     */
    public function debug($message, array $context = [])
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }
}
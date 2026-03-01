<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Logging;

/**
 * Main Logger class that manages multiple writers (adapters).
 */
final class Logger
{
    // Logging levels based on RFC 5424
    const EMERGENCY = 0;
    const ALERT     = 1;
    const CRITICAL  = 2;
    const ERROR     = 3;
    const WARNING   = 4;
    const NOTICE    = 5;
    const INFO      = 6;
    const DEBUG     = 7;

    /**
     * @var LogWriterInterface[]
     */
    private array $writers = [];

    /**
     * Adds a writer (adapter) to the logger.
     * 
     * @param LogWriterInterface $writer
     * @return void
     */
    public function addWriter(LogWriterInterface $writer)
    {
        $this->writers[] = $writer;
    }

    /**
     * Logs a message with a specific level and context.
     *
     * @param int $level
     * @param string $message
     * @param array $context
     * @return boolean
     */
    public function log(int $level, string $message, array $context = [])
    {
        foreach ($this->writers as $writer) {
            $writer->write($level, $message, $context);
        }

        return true;
    }

    /**
     * System is unusable.
     * 
     * @param string $message
     * @param array $context
     * @return boolean
     */
    public function emergency(string $message, array $context = [])
    {
        return $this->log(self::EMERGENCY, $message, $context);
    }

    /**
     * Action must be taken immediately.
     * 
     * @param string $message
     * @param array $context
     * @return boolean
     */
    public function alert(string $message, array $context = [])
    {
        return $this->log(self::ALERT, $message, $context);
    }

    /**
     * Critical conditions.
     * 
     * @param string $message
     * @param array $context
     * @return boolean
     */
    public function critical(string $message, array $context = [])
    {
        return $this->log(self::CRITICAL, $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically be logged and monitored.
     * 
     * @param string $message
     * @param array $context
     * @return boolean
     */
    public function error(string $message, array $context = [])
    {
        return $this->log(self::ERROR, $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     * 
     * @param string $message
     * @param array $context
     * @return boolean
     */
    public function warning(string $message, array $context = [])
    {
        return $this->log(self::WARNING, $message, $context);
    }

    /**
     * Normal but significant events.
     * 
     * @param string $message
     * @param array $context
     * @return boolean
     */
    public function notice(string $message, array $context = [])
    {
        return $this->log(self::NOTICE, $message, $context);
    }

    /**
     * Interesting events.
     * 
     * @param string $message
     * @param array $context
     * @return boolean
     */
    public function info(string $message, array $context = [])
    {
        return $this->log(self::INFO, $message, $context);
    }

    /**
     * Detailed debug information.
     * 
     * @param string $message
     * @param array $context
     * @return boolean
     */
    public function debug(string $message, array $context = [])
    {
        return $this->log(self::DEBUG, $message, $context);
    }
}

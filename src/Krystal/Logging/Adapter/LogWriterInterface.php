<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Logging\Adapter;

/**
 * Interface for log writers (adapters).
 */
interface LogWriterInterface
{
    /**
     * Writes a log message.
     *
     * @param int $level The logging level (e.g., constants from Logger class).
     * @param string $message The message to log.
     * @param array $context Contextual data array.
     * @return bool True on success, false on failure.
     */
    public function write(int $level, string $message, array $context = []);
}

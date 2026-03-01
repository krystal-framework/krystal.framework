<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Logging\Adapter;

/**
 * Adapter for writing log messages to a file.
 */
final class FileWriter implements LogWriterInterface
{
    /**
     * File path
     * 
     * @var string
     */
    private string $filePath;

    /**
     * FileWriter constructor.
     *
     * @param string $filePath Path to the log file.
     */
    public function __construct(string $filePath)
    {
        if ($filePath === '') {
            throw new InvalidArgumentException('Log file path cannot be empty');
        }

        $dir = dirname($filePath);

        if (!is_dir($dir) && !@mkdir($dir, 0755, true)) {
            throw new RuntimeException("Cannot create log directory: $dir");
        }

        if (!is_writable($dir)) {
            throw new RuntimeException("Log directory is not writable: $dir");
        }

        $this->filePath = $filePath;
    }

    /**
     * Writes the log message to the file.
     *
     * @param int $level The logging level.
     * @param string $message The log message.
     * @param array $context Contextual data array.
     * @return bool True on success, false on failure.
     */
    public function write(int $level, string $message, array $context = []): bool
    {
        $date = date('Y-m-d H:i:s');

        // Encode context data to JSON for logging
        $contextString = !empty($context) ? ' ' . json_encode($context) : '';
        
        // Format the log line
        $formattedMessage = "[$date] Level: $level - $message$contextString" . PHP_EOL;

        // Append message to file and return success status
        return file_put_contents($this->filePath, $formattedMessage, FILE_APPEND) !== false;
    }
}

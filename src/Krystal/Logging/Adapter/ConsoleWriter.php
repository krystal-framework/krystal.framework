<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Logging\Adapter;

/**
 * Adapter for writing log messages to the console (stderr).
 */
final class ConsoleWriter implements LogWriterInterface
{
    /**
     * Writes the log message to the standard error stream.
     *
     * @param int $level The logging level.
     * @param string $message The log message.
     * @param array $context Contextual data array.
     * @return bool True on success, false on failure.
     */
    public function write(int $level, string $message, array $context = [])
    {
        $formattedMessage = "[$level] $message" . PHP_EOL;

        if (!empty($context)) {
            // Pretty-print context with indentation
            $formattedMessage .= "Context: " . json_encode($context, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
        }

        if (defined('STDERR')) {
            $result = fwrite(STDERR, $formattedMessage);
        } else {
            $stderr = fopen('php://stderr', 'w');

            if ($stderr) {
                $result = fwrite($stderr, $formattedMessage);
                fclose($stderr);
            } else {
                $result = false;
            }
        }

        return $result !== false;
    }
}

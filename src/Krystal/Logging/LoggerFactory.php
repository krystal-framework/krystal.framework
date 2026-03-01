<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Logging;

use InvalidArgumentException;
use RuntimeException;
use Krystal\Logging\Adapter\FileWriter;
use Krystal\Logging\Adapter\ConsoleWriter;

/**
 * Factory for creating configured Logger instances.
 */
final class LoggerFactory
{
    /**
     * Builds a Logger instance based on configuration array.
     *
     * @param array $config Configuration array.
     * @return \Krystal\Logging\Logger
     * @throws InvalidArgumentException|RuntimeException
     */
    public static function build(array $config)
    {
        $logger = new Logger();

        if (!isset($config['writers']) || !is_array($config['writers'])) {
            throw new InvalidArgumentException("Configuration must contain a 'writers' array.");
        }

        foreach ($config['writers'] as $writerConfig) {
            if (!isset($writerConfig['type'])) {
                throw new InvalidArgumentException("Writer configuration must have a 'type'.");
            }

            switch ($writerConfig['type']) {
                case 'file':
                    if (!isset($writerConfig['path']) || !is_string($writerConfig['path']) || trim($writerConfig['path']) === '') {
                        throw new InvalidArgumentException("FileWriter requires a valid non-empty 'path' string.");
                    }

                    $logger->addWriter(new FileWriter($writerConfig['path']));
                break;

                case 'console':
                    $logger->addWriter(new ConsoleWriter());
                break;

                default:
                    throw new InvalidArgumentException("Unknown writer type: " . $writerConfig['type']);
            }
        }

        return $logger;
    }
}

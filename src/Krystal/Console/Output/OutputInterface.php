<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Console\Output;

/**
 * Contract for writing output to the console.
 */
interface OutputInterface
{
    /**
     * Writes a message to the output
     *
     * @param string $message
     * @param bool   $newline Whether to append a newline
     * @return void
     */
    public function write($message, $newline = false);

    /**
     * Writes a message followed by a newline
     *
     * @param string $message
     * @return void
     */
    public function writeln($message = '');

    /**
     * Writes a success message
     *
     * @param string $message
     * @return void
     */
    public function success($message);

    /**
     * Writes an error message
     *
     * @param string $message
     * @return void
     */
    public function error($message);

    /**
     * Writes a warning message
     *
     * @param string $message
     * @return void
     */
    public function warning($message);

    /**
     * Writes a note / informational message
     *
     * @param string $message
     * @return void
     */
    public function note($message);

    /**
     * Writes a title (usually highlighted)
     *
     * @param string $message
     * @return void
     */
    public function title($message);
}
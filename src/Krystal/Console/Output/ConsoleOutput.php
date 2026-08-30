<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Console\Output;

/**
 * Standard console output implementation with optional ANSI colors.
 */
final class ConsoleOutput implements OutputInterface
{
    /**
     * Whether ANSI colors are enabled
     *
     * @var bool
     */
    private $decorated;

    /**
     * State initialization
     *
     * @param bool $decorated Whether to use ANSI colors when supported
     */
    public function __construct($decorated = true)
    {
        $this->decorated = $decorated && $this->hasColorSupport();
    }

    /**
     * {@inheritdoc}
     */
    public function write($message, $newline = false)
    {
        echo $message . ($newline ? PHP_EOL : '');
    }

    /**
     * {@inheritdoc}
     */
    public function writeln($message = '')
    {
        $this->write($message, true);
    }

    /**
     * {@inheritdoc}
     */
    public function success($message)
    {
        $this->writeln($this->color('green', ' [OK] ') . $message);
    }

    /**
     * {@inheritdoc}
     */
    public function error($message)
    {
        $this->writeln($this->color('red', ' [ERROR] ') . $message);
    }

    /**
     * {@inheritdoc}
     */
    public function warning($message)
    {
        $this->writeln($this->color('yellow', ' [WARNING] ') . $message);
    }

    /**
     * {@inheritdoc}
     */
    public function note($message)
    {
        $this->writeln($this->color('cyan', ' [NOTE] ') . $message);
    }

    /**
     * {@inheritdoc}
     */
    public function title($message)
    {
        $this->writeln('');
        $this->writeln($this->color('bright', $message));
        $this->writeln($this->color('bright', str_repeat('=', strlen($message))));
        $this->writeln('');
    }

    /**
     * Applies ANSI color to the given text when decoration is enabled
     *
     * @param string $color
     * @param string $text
     * @return string
     */
    private function color($color, $text)
    {
        if (!$this->decorated) {
            return $text;
        }

        $colors = [
            'red'    => "\033[31m",
            'green'  => "\033[32m",
            'yellow' => "\033[33m",
            'cyan'   => "\033[36m",
            'bright' => "\033[1m",
            'reset'  => "\033[0m",
        ];

        return (isset($colors[$color]) ? $colors[$color] : '') . $text . $colors['reset'];
    }

    /**
     * Detects whether the current output supports ANSI colors
     *
     * @return bool
     */
    private function hasColorSupport()
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            return false;
        }

        return function_exists('posix_isatty') && @posix_isatty(STDOUT);
    }
}
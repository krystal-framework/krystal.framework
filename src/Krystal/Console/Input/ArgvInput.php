<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Console\Input;

/**
 * Parses command line arguments from $_SERVER['argv'].
 *
 * Supports:
 *  - Long options:  --path=value  /  --path value
 *  - Short options: -f  /  -abc
 *  - Regular arguments
 */
final class ArgvInput implements InputInterface
{
    /**
     * Raw tokens received from the command line
     *
     * @var array
     */
    private $tokens = [];

    /**
     * Parsed positional arguments
     *
     * @var array
     */
    private $parsedArguments = [];

    /**
     * Parsed options
     *
     * @var array
     */
    private $parsedOptions = [];

    /**
     * The first argument (usually the command name)
     *
     * @var string|null
     */
    private $firstArgument = null;

    /**
     * State initialization
     *
     * @param array|null $argv Optional custom argv (useful for testing).
     *                         Defaults to $_SERVER['argv'].
     */
    public function __construct($argv = null)
    {
        if ($argv === null) {
            $argv = isset($_SERVER['argv']) ? $_SERVER['argv'] : [];
        }

        // Remove the script name
        array_shift($argv);

        $this->tokens = $argv;
        $this->parse();
    }

    /**
     * Parses the raw tokens into arguments and options
     *
     * @return void
     */
    private function parse()
    {
        $arguments = [];
        $options   = [];
        $count     = count($this->tokens);

        for ($i = 0; $i < $count; $i++) {
            $token = $this->tokens[$i];

            // Long option: --name or --name=value
            if (strpos($token, '--') === 0) {
                $name  = substr($token, 2);
                $value = true;

                if (strpos($name, '=') !== false) {
                    $parts = explode('=', $name, 2);
                    $name  = $parts[0];
                    $value = $parts[1];
                } elseif (isset($this->tokens[$i + 1]) && strpos($this->tokens[$i + 1], '-') !== 0) {
                    $value = $this->tokens[++$i];
                }

                $options[$name] = $value;
                continue;
            }

            // Short option(s): -f or -abc
            if (strpos($token, '-') === 0 && $token !== '-') {
                $chars = str_split(substr($token, 1));

                foreach ($chars as $char) {
                    $options[$char] = true;
                }
                continue;
            }

            // Regular argument
            $arguments[] = $token;
        }

        $this->parsedArguments = $arguments;
        $this->parsedOptions   = $options;
        $this->firstArgument   = isset($arguments[0]) ? $arguments[0] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstArgument()
    {
        return $this->firstArgument;
    }

    /**
     * {@inheritdoc}
     */
    public function getArgument($name, $default = null)
    {
        return isset($this->parsedArguments[$name]) ? $this->parsedArguments[$name] : $default;
    }

    /**
     * {@inheritdoc}
     */
    public function getArguments()
    {
        return $this->parsedArguments;
    }

    /**
     * {@inheritdoc}
     */
    public function getOption($name, $default = null)
    {
        return isset($this->parsedOptions[$name]) ? $this->parsedOptions[$name] : $default;
    }

    /**
     * {@inheritdoc}
     */
    public function hasOption($name)
    {
        return array_key_exists($name, $this->parsedOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->parsedOptions;
    }

    /**
     * {@inheritdoc}
     */
    public function getRawArguments()
    {
        return $this->tokens;
    }
}
<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Console\Input;

/**
 * Represents the input of a console command (arguments + options).
 */
interface InputInterface
{
    /**
     * Returns the first argument from the command line.
     *
     * This is usually the command name (e.g. "migration:migrate").
     *
     * @return string|null
     */
    public function getFirstArgument();

    /**
     * Returns a specific argument by name.
     *
     * @param string $name
     * @param mixed  $default
     * @return mixed
     */
    public function getArgument($name, $default = null);

    /**
     * Returns all parsed arguments.
     *
     * @return array
     */
    public function getArguments();

    /**
     * Returns the value of an option.
     *
     * @param string $name
     * @param mixed  $default
     * @return mixed
     */
    public function getOption($name, $default = null);

    /**
     * Checks whether an option was provided.
     *
     * @param string $name
     * @return bool
     */
    public function hasOption($name);

    /**
     * Returns all parsed options.
     *
     * @return array
     */
    public function getOptions();

    /**
     * Returns the raw list of tokens received from the command line.
     *
     * @return array
     */
    public function getRawArguments();
}
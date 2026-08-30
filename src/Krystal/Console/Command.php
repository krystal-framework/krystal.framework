<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Console;

use Krystal\Console\Input\InputInterface;
use Krystal\Console\Output\OutputInterface;

/**
 * Base class for all console commands.
 */
abstract class Command
{
    /**
     * Returns the unique name of the command.
     *
     * Example: "migration:migrate"
     *
     * @return string
     */
    abstract public function getName();

    /**
     * Returns a short description of the command
     *
     * @return string
     */
    public function getDescription()
    {
        return '';
    }

    /**
     * Returns alternative names (aliases) for the command
     *
     * @return string[]
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * Executes the command.
     *
     * Should return 0 on success, or any other integer as an error code.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int
     */
    abstract protected function execute(InputInterface $input, OutputInterface $output);

    /**
     * Runs the command.
     *
     * This is the public entry point called by the Application.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int Exit code (0 = success)
     */
    final public function run(InputInterface $input, OutputInterface $output)
    {
        return $this->execute($input, $output);
    }
}
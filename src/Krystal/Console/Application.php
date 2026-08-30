<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Console;

use Krystal\Console\Input\ArgvInput;
use Krystal\Console\Input\InputInterface;
use Krystal\Console\Output\ConsoleOutput;
use Krystal\Console\Output\OutputInterface;
use Throwable;

/**
 * Main console application.
 *
 * Responsible for registering commands and running the requested one.
 */
final class Application
{
    /**
     * Application name
     *
     * @var string
     */
    private $name;

    /**
     * Application version
     *
     * @var string
     */
    private $version;

    /**
     * Registered commands
     *
     * @var Command[]
     */
    private $commands = [];

    /**
     * State initialization
     *
     * @param string $name Application name
     * @param string $version Application version
     */
    public function __construct($name = 'Krystal Console', $version = '1.0.0')
    {
        $this->name    = $name;
        $this->version = $version;
    }

    /**
     * Registers a command
     *
     * @param Command $command
     * @return self
     */
    public function add(Command $command)
    {
        $this->commands[$command->getName()] = $command;

        foreach ($command->getAliases() as $alias) {
            $this->commands[$alias] = $command;
        }

        return $this;
    }

    /**
     * Runs the application
     *
     * @param InputInterface|null $input
     * @param OutputInterface|null $output
     * @return int Exit code
     */
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        $input  = $input  !== null ? $input  : new ArgvInput();
        $output = $output !== null ? $output : new ConsoleOutput();

        $name = $input->getFirstArgument();

        if ($name === null || $name === 'list' || $input->hasOption('help') || $input->hasOption('h')) {
            $this->renderHelp($output);
            return 0;
        }

        if (!isset($this->commands[$name])) {
            $output->error(sprintf('Command "%s" is not defined.', $name));
            $this->renderHelp($output);
            return 1;
        }

        try {
            return $this->commands[$name]->run($input, $output);
        } catch (Throwable $e) {
            $output->error($e->getMessage());
            return 1;
        }
    }

    /**
     * Renders the list of available commands
     *
     * @param OutputInterface $output
     * @return void
     */
    private function renderHelp(OutputInterface $output)
    {
        $output->writeln(sprintf('%s %s', $this->name, $this->version));
        $output->writeln('');
        $output->writeln('Available commands:');

        $unique = [];
        foreach ($this->commands as $command) {
            $unique[$command->getName()] = $command;
        }

        ksort($unique);

        foreach ($unique as $command) {
            $output->writeln(sprintf(
                '  %-25s %s',
                $command->getName(),
                $command->getDescription()
            ));
        }

        $output->writeln('');
    }
}
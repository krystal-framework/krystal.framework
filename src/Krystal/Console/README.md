Console
===
The Console component provides a simple and clean API for building command-line applications. It allows you to define commands, parse arguments and options, and write formatted output to the terminal.

Import the required classes:

    <?php
    
    use Krystal\Console\Application;
    use Krystal\Console\Command;
    use Krystal\Console\Input\InputInterface;
    use Krystal\Console\Output\OutputInterface;

## Creating a command
Every command must extend the base `Command` class and implement two methods: `getName()` and `execute()`.

    <?php
    
    namespace Site\Console;
    
    use Krystal\Console\Command;
    use Krystal\Console\Input\InputInterface;
    use Krystal\Console\Output\OutputInterface;
    
    final class HelloCommand extends Command
    {
        public function getName()
        {
            return 'hello';
        }
    
        public function getDescription()
        {
            return 'Prints a greeting message';
        }
    
        protected function execute(InputInterface $input, OutputInterface $output)
        {
            $name = $input->getOption('name', 'World');
    
            $output->writeln('Hello, ' . $name . '!');
    
            return 0; // 0 means success
        }
    }

## Registering and running commands

Create an entry point file called `console.php` in the root of your project. Then create an `Application` instance, register your commands, and call `run()`.

    <?php

    // Prevent access from web browser
    if (PHP_SAPI !== 'cli') {
        header('HTTP/1.1 403 Forbidden');
        exit('This script can only be run from the command line.');
    }
    
    require __DIR__ . '/vendor/autoload.php';
    
    use Krystal\Console\Application;
    use Site\Console\HelloCommand;
    
    $app = new Application('My Application', '1.0.0');
    
    $app->add(new HelloCommand());
    
    exit($app->run());

You can now execute commands from the root of your project:

    php console.php hello
    php console.php hello --name=John

## Working with options
Options can be passed in two formats:

    php console.php hello --name=John
    php console.php hello --name John

Reading options inside a command:

    // Get option value (with default)
    $name = $input->getOption('name', 'World');

    // Check if option exists
    if ($input->hasOption('force')) {
        // ...
    }
    
    // Get all options
    $options = $input->getOptions();

## Working with arguments
Arguments are positional values passed after the command name.

    php console.php hello John

Reading arguments:

    $arguments = $input->getArguments();
    
    // First argument after the command name
    $first = $arguments[1] ?? null;

## Output helpers
The `OutputInterface` provides several convenient methods for writing messages:

    $output->writeln('Simple line');
    
    $output->title('Work in progress');
    
    $output->success('Operation completed successfully');
    $output->error('Something went wrong');
    $output->warning('Please check your configuration');
    $output->note('This is an informational message');

## Complete example

    <?php

    namespace Site\Console;

    use Krystal\Console\Command;
    use Krystal\Console\Input\InputInterface;
    use Krystal\Console\Output\OutputInterface;

    final class CacheClearCommand extends Command
    {
        public function getName()
        {
            return 'cache:clear';
        }

        public function getDescription()
        {
            return 'Clear the application cache';
        }

        protected function execute(InputInterface $input, OutputInterface $output)
        {
            $force = $input->hasOption('force');

            $output->title('Clearing cache');

            if (!$force) {
                $output->warning('This operation will remove all cached data');
                $output->note('Use --force to skip this warning');
            }

            // Your cache clearing logic here...

            $output->success('Cache has been cleared successfully');

            return 0;
        }
    }

Usage:

    php console.php cache:clear
    php console.php cache:clear --force

## Listing available commands
Running the application without any command (or with `list`) displays all registered commands:

    php console.php
    php console.php list

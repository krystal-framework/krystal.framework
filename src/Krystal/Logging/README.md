
Logging
======

Logging helps you track application behavior, diagnose bugs, and analyze performance by recording events, context, and errors to various destinations without impacting functionality.

## Features

-   **Adapter Pattern:** Send logs to multiple destinations (files, console, etc.) simultaneously.
-   **Factory Pattern:** Easily configure and initialize the logger via an array.
-   **Standard Levels:** Supports all RFC 5424 logging levels.
-   **Context Support:** Pass contextual data arrays with log messages.
    

## Usage

### 1. Basic Setup (Manual)

    <?php
    
    use Krystal\Logging\Logger;
    use Krystal\Logging\Adapter\FileWriter;
    use Krystal\Logging\Adapter\ConsoleWriter;
    
    // Create the logger instance
    $logger = new Logger();
    
    // Add writers (adapters)
    $logger->addWriter(new FileWriter(__DIR__ . '/app.log'));
    $logger->addWriter(new ConsoleWriter());
    
    // Log messages
    $logger->info('Application started');
    $logger->error('Database connection failed', ['host' => 'localhost', 'port' => 3306]);

### 2. Setup via Factory (Recommended)

The `LoggerFactory` allows you to define configuration in an array, making it easy to integrate with configuration files.

    <?php
    
    use Krystal\Logging\LoggerFactory;
    
    $logger = LoggerFactory::build([
        'writers' => [
            [
                'type' => 'file',
                'path' => __DIR__ . '/app.log'
            ],
            [
                'type' => 'console'
            ]
        ]
    ]);
    
    // Log messages
    $logger->warning('This is a warning message');

## Logging Levels

The library supports the following levels (ordered from highest to lowest severity):

| Method                        | Level | Description                                      |
|-------------------------------|-------|--------------------------------------------------|
| `$logger->emergency()`        | 0     | System is unusable.                              |
| `$logger->alert()`            | 1     | Action must be taken immediately.                |
| `$logger->critical()`         | 2     | Critical conditions.                             |
| `$logger->error()`            | 3     | Runtime errors.                                  |
| `$logger->warning()`          | 4     | Exceptional occurrences that are not errors.     |
| `$logger->notice()`           | 5     | Normal but significant events.                   |
| `$logger->info()`             | 6     | Interesting events.                              |
| `$logger->debug()`            | 7     | Detailed debug information.                      |

## Creating Custom Adapters

To create a new adapter, implement the `Krystal\Logging\Adapter\LogWriterInterface`.

    <?php
    
    use Krystal\Logging\Adapter\LogWriterInterface;
    
    class SlackWriter implements LogWriterInterface
    {
        public function write(int $level, string $message, array $context = []): bool
        {
            // Custom logic to send message to Slack
            return true;
        }
    }

Then add it to the factory switch case or manually add it:

    $logger->addWriter(new SlackWriter());
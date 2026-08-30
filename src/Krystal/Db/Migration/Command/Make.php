<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Migration\Command;

use Krystal\Console\Command as BaseCommand;
use Krystal\Console\Input\InputInterface;
use Krystal\Console\Output\OutputInterface;
use RuntimeException;

final class Make extends BaseCommand
{
    /**
     * Default migration path
     * 
     * @var string
     */
    private $defaultPath;

    /**
     * State initialization
     * 
     * @param string $defaultPath
     * @return void
     */
    public function __construct($defaultPath)
    {
        $this->defaultPath = $defaultPath;
    }

    /**
     * Get command name
     * 
     * @return string
     */
    public function getName()
    {
        return 'migration:make';
    }

    /**
     * Get command description
     * 
     * @return string
     */
    public function getDescription()
    {
        return 'Create a new migration file';
    }

    /**
     * Execute the command
     * 
     * @param \Krystal\Console\Input\InputInterface $input
     * @param \Krystal\Console\Output\OutputInterface $output
     * @return integer
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $arguments = $input->getArguments();
        $name = $arguments[1] ?? null;

        if (!$name) {
            $output->error('The migration name argument is missing.');
            return 1;
        }

        $path = $input->getOption('path', $this->defaultPath);

        if (!is_dir($path)) {
            if (!mkdir($path, 0755, true)) {
                throw new RuntimeException(sprintf('Directory "%s" could not be created.', $path));
            }
        }

        $timestamp = date('YmdHis');
        $filename = $timestamp . '_' . $name . '.php';
        $filePath = rtrim($path, '/') . '/' . $filename;

        $stub = $this->getMigrationStub($name);

        if (file_put_contents($filePath, $stub) === false) {
            throw new RuntimeException(sprintf('Failed to write migration file "%s".', $filePath));
        }

        $output->success('Migration created successfully: ' . $filename);

        return 0;
    }

    /**
     * Get migration stub code
     * 
     * @param string $className
     * @return string
     */
    private function getMigrationStub($className)
    {
        $templatePath = __DIR__ . '/../View/stub.phtml';

        if (!file_exists($templatePath)) {
            throw new RuntimeException(sprintf('Migration stub template not found at "%s".', $templatePath));
        }

        ob_start();
        include $templatePath;
        return ob_get_clean();
    }
}
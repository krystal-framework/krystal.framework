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
use Krystal\Db\Migration\Migrator;

final class Migrate extends BaseCommand
{
    /**
     * Migrator instance
     * 
     * @var \Krystal\Db\Migration\Migrator
     */
    private $migrator;

    /**
     * State initialization
     * 
     * @param \Krystal\Db\Migration\Migrator $migrator
     * @return void
     */
    public function __construct(Migrator $migrator)
    {
        $this->migrator = $migrator;
    }

    /**
     * Get command name
     * 
     * @return string
     */
    public function getName()
    {
        return 'migration:migrate';
    }

    /**
     * Get command description
     * 
     * @return string
     */
    public function getDescription()
    {
        return 'Run the database migrations';
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
        $customPath = $input->getOption('path');

        if ($customPath) {
            $this->migrator->addPath($customPath);
        }

        $output->title('Running migrations');

        $ran = $this->migrator->run();

        if (empty($ran)) {
            $output->note('Nothing to migrate.');
            return 0;
        }

        foreach ($ran as $version) {
            $output->writeln('Migrated: ' . $version);
        }

        $output->success('Migrations completed successfully.');

        return 0;
    }
}
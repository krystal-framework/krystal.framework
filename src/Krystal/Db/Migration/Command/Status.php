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

final class Status extends BaseCommand
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
        return 'migration:status';
    }

    /**
     * Get command description
     * 
     * @return string
     */
    public function getDescription()
    {
        return 'Show the status of each migration';
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
        $output->title('Migration Status');

        $status = $this->migrator->getStatus();

        if (empty($status)) {
            $output->note('No migrations found.');
            return 0;
        }

        foreach ($status as $version => $applied) {
            $label = $applied ? '[Applied]' : '[Pending]';
            $output->writeln(sprintf('%s %s', $label, $version));
        }

        return 0;
    }
}
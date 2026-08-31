<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Migration;

use Krystal\Console\Application;
use Krystal\Db\Migration\Command\Migrate;
use Krystal\Db\Migration\Command\Rollback;
use Krystal\Db\Migration\Command\Status;
use Krystal\Db\Migration\Command\Make;
use Krystal\Db\Sql\DbInterface;

final class MigrationRunner
{
    /**
     * Database instance
     * 
     * @var \Krystal\Db\Sql\DbInterface
     */
    private $db;

    /**
     * Migration directory path
     * 
     * @var string
     */
    private $path;

    /**
     * State initialization
     * 
     * @param \Krystal\Db\Sql\DbInterface $db
     * @param string $path
     * @return void
     */
    public function __construct(DbInterface $db, $path)
    {
        $this->db = $db;
        $this->path = $path;
    }

    /**
     * Run the console application
     * 
     * @return integer
     */
    public function run()
    {
        $app = new Application('Krystal Migrations', '1.0.0');

        $repository = new MigrationRepository($this->db);

        $migrator = new Migrator($repository, $this->db);
        $migrator->addPath($this->path);

        $app->add(new Migrate($migrator));
        $app->add(new Rollback($migrator));
        $app->add(new Status($migrator));
        $app->add(new Make($this->path));

        return $app->run();
    }
}
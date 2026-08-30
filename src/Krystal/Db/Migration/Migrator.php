<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Migration;

use Krystal\Db\Sql\DbInterface;
use RuntimeException;

class Migrator
{
    /**
     * Migration repository instance
     * 
     * @var \Krystal\Db\Migration\MigrationRepositoryInterface
     */
    private $repository;

    /**
     * Database instance
     * 
     * @var \Krystal\Db\Sql\DbInterface
     */
    private $db;

    /**
     * Paths to migration directories
     * 
     * @var array
     */
    private $paths = [];

    /**
     * State initialization
     * 
     * @param \Krystal\Db\Migration\MigrationRepositoryInterface $repository
     * @param \Krystal\Db\Sql\DbInterface $db
     * @return void
     */
    public function __construct(MigrationRepositoryInterface $repository, DbInterface $db)
    {
        $this->repository = $repository;
        $this->db = $db;
    }

    /**
     * Add migration path
     * 
     * @param string $path
     * @return void
     */
    public function addPath($path)
    {
        $this->paths[] = $path;
    }

    /**
     * Set migration paths
     * 
     * @param array $paths
     * @return void
     */
    public function setPaths(array $paths)
    {
        $this->paths = $paths;
    }

    /**
     * Run pending migrations
     * 
     * @return array
     */
    public function run()
    {
        if (!$this->repository->repositoryExists()) {
            $this->repository->createRepository();
        }

        $files = $this->getMigrationFiles();
        $applied = $this->repository->getAppliedMigrations();

        $pending = array_diff($files, $applied);

        if (empty($pending)) {
            return [];
        }

        $batch = $this->repository->getLastBatchNumber() + 1;
        $ran = [];

        foreach ($pending as $version) {
            $this->runMigration($version, $batch);
            $ran[] = $version;
        }

        return $ran;
    }

    /**
     * Rollback last migration batch
     * 
     * @return array
     */
    public function rollback()
    {
        if (!$this->repository->repositoryExists()) {
            return [];
        }

        $batch = $this->repository->getLastBatchNumber();
        $migrations = $this->repository->getMigrationsByBatch($batch);

        if (empty($migrations)) {
            return [];
        }

        $rolledBack = [];

        foreach ($migrations as $migration) {
            $this->rollbackMigration($migration);
            $rolledBack[] = $migration['version'];
        }

        return $rolledBack;
    }

    /**
     * Get all available migration files sorted by version
     * 
     * @return array
     */
    public function getMigrationFiles()
    {
        $files = [];

        foreach ($this->paths as $path) {
            if (!is_dir($path)) {
                continue;
            }

            foreach (glob($path . '/*.php') as $file) {
                $filename = basename($file, '.php');
                $files[] = $filename;
            }
        }

        sort($files);
        return $files;
    }

    /**
     * Get status of all migrations
     * 
     * @return array
     */
    public function getStatus()
    {
        if (!$this->repository->repositoryExists()) {
            return [];
        }

        $files = $this->getMigrationFiles();
        $applied = $this->repository->getAppliedMigrations();

        $status = [];

        foreach ($files as $file) {
            $status[$file] = in_array($file, $applied);
        }

        return $status;
    }

    /**
     * Run a single migration
     * 
     * @param string $version
     * @param integer $batch
     * @return void
     */
    private function runMigration($version, $batch)
    {
        $instance = $this->resolveMigrationInstance($version);
        $instance->up($this->db);

        $className = get_class($instance);
        $this->repository->log($version, $className, $batch);
    }

    /**
     * Rollback a single migration
     * 
     * @param array $migration
     * @return void
     */
    private function rollbackMigration(array $migration)
    {
        $version = $migration['version'];
        $instance = $this->resolveMigrationInstance($version);
        $instance->down($this->db);

        $this->repository->delete($version);
    }

    /**
     * Resolve migration instance from version name
     * 
     * @param string $version
     * @throws \RuntimeException
     * @return \Krystal\Db\Migration\MigrationInterface
     */
    private function resolveMigrationInstance($version)
    {
        foreach ($this->paths as $path) {
            $file = $path . '/' . $version . '.php';

            if (file_exists($file)) {
                // Check if the file returns an anonymous class instance directly
                $instance = include $file;

                if ($instance instanceof MigrationInterface) {
                    return $instance;
                }

                // Fallback to traditional named class lookup for legacy files
                $matchedClass = $this->findMigrationClassInFile($file);

                if ($matchedClass && class_exists($matchedClass)) {
                    $instance = new $matchedClass();
                    if ($instance instanceof MigrationInterface) {
                        return $instance;
                    }
                }
            }
        }

        throw new RuntimeException(sprintf('Migration class for version "%s" could not be resolved.', $version));
    }

    /**
     * Find migration class name defined in file
     * 
     * @param string $filePath
     * @return string|null
     */
    private function findMigrationClassInFile($filePath)
    {
        $classes = get_declared_classes();
        include_once $filePath;
        $newClasses = array_diff(get_declared_classes(), $classes);

        foreach ($newClasses as $class) {
            if (in_array(MigrationInterface::class, class_implements($class))) {
                return $class;
            }
        }

        return null;
    }
}
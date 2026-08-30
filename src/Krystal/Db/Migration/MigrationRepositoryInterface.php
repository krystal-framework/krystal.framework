<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Migration;

interface MigrationRepositoryInterface
{
    /**
     * Get list of applied migration versions
     * 
     * @return array
     */
    public function getAppliedMigrations();

    /**
     * Get last batch number
     * 
     * @return integer
     */
    public function getLastBatchNumber();

    /**
     * Get migrations by batch number
     * 
     * @param integer $batch
     * @return array
     */
    public function getMigrationsByBatch($batch);

    /**
     * Log that a migration was applied
     * 
     * @param string $version
     * @param string $className
     * @param integer $batch
     * @return void
     */
    public function log($version, $className, $batch);

    /**
     * Remove migration log entry
     * 
     * @param string $version
     * @return void
     */
    public function delete($version);

    /**
     * Check if migration repository table exists
     * 
     * @return boolean
     */
    public function repositoryExists();

    /**
     * Create migration repository table
     * 
     * @return void
     */
    public function createRepository();
}
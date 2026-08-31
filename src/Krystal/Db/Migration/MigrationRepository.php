<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Migration;

use Krystal\Db\Sql\DbInterface;
use DateTime;

final class MigrationRepository implements MigrationRepositoryInterface
{
    /**
     * Database instance
     * 
     * @var \Krystal\Db\Sql\DbInterface
     */
    private $db;

    /**
     * Migration tracking table name
     * 
     * @var string
     */
    private $table = 'krystal_migrations';

    /**
     * State initialization
     * 
     * @param \Krystal\Db\Sql\DbInterface $db
     * @return void
     */
    public function __construct(DbInterface $db)
    {
        $this->db = $db;
    }

    /**
     * Get list of applied migration versions
     * 
     * @return array
     */
    public function getAppliedMigrations()
    {
        return $this->db->select('version')
                        ->from($this->table)
                        ->orderBy('id', 'ASC')
                        ->queryAll('version');
    }

    /**
     * Get last batch number
     * 
     * @return integer
     */
    public function getLastBatchNumber()
    {
        $batch = $this->db->select('MAX(batch) AS batch')
                          ->from($this->table)
                          ->queryScalar();

        return $batch ? (int) $batch : 0;
    }

    /**
     * Get migrations by batch number
     * 
     * @param integer $batch
     * @return array
     */
    public function getMigrationsByBatch($batch)
    {
        return $this->db->select('*')
                        ->from($this->table)
                        ->whereEquals('batch', $batch)
                        ->orderBy('id', 'DESC')
                        ->queryAll();
    }

    /**
     * Log that a migration was applied
     * 
     * @param string $version
     * @param string $className
     * @param integer $batch
     * @return void
     */
    public function log($version, $className, $batch)
    {
        $date = new DateTime();

        $data = [
            'version' => $version,
            'class_name' => $className,
            'batch' => $batch,
            'applied_at' => $date->format('Y-m-d H:i:s'),
        ];

        $this->db->insert($this->table, $data)
                 ->execute();
    }

    /**
     * Remove migration log entry
     * 
     * @param string $version
     * @return void
     */
    public function delete($version)
    {
        $this->db->delete()
                 ->from($this->table)
                 ->whereEquals('version', $version)
                 ->execute();
    }

    /**
     * Check if migration repository table exists
     * 
     * @return boolean
     */
    public function repositoryExists()
    {
        $tables = $this->db->fetchAllTables();
        return in_array($this->table, $tables);
    }

    /**
     * Create migration repository table
     * 
     * @return void
     */
    public function createRepository()
    {
        $this->db->createTable($this->table, [
            'id'         => 'INT UNSIGNED AUTO_INCREMENT PRIMARY KEY',
            'version'    => 'VARCHAR(255) NOT NULL UNIQUE',
            'class_name' => 'VARCHAR(255) NOT NULL',
            'batch'      => 'INT UNSIGNED NOT NULL',
            'applied_at' => 'DATETIME NOT NULL',
        ], 'InnoDB');
    }
}
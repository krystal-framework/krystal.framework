<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Migration;

use Krystal\Db\Sql\DbInterface;

interface MigrationInterface
{
    /**
     * Run the migration
     * 
     * @param \Krystal\Db\Sql\DbInterface $db
     * @return void
     */
    public function up(DbInterface $db);

    /**
     * Reverse the migration
     * 
     * @param \Krystal\Db\Sql\DbInterface $db
     * @return void
     */
    public function down(DbInterface $db);
}
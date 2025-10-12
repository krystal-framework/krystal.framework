<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Config\Sql;

use Krystal\Serializer\NativeSerializer as Serializer;

abstract class SqlConfigServiceFactory
{
    /**
     * Builds configuration service
     * 
     * @param \PDO $pdo Prepared PDO instance
     * @param string $table Table name to work with
     * @return \Krystal\Config\Sql\SqlConfigService
     */
    public static function build($pdo, $table)
    {
        $configMapper = new ConfigMapper(new Serializer(), $pdo, $table);
        return new SqlConfigService($configMapper, new ArrayConfig());
    }
}

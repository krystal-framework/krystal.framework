<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Sql\Connector;

use PDO;

final class MySQL implements ConnectorInterface
{
    /**
     * {@inheritDoc}
     */
    public function getArgs(array $config)
    {
        $dsn = 'mysql:host='.$config['host'];

        if (isset($config['dbname'])) {
            $dsn .= ';dbname='.$config['dbname'];
        }

        $dsn .= ';charset=utf8mb4';

        return array($dsn, $config['username'], $config['password'], array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => true,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // Disable STRICT mode
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET sql_mode="IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION"'
        ));
    }
}

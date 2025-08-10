<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Sql\Connector;

use PDO;

final class PostgreSQL implements ConnectorInterface
{
    /**
     * {@inheritDoc}
     */
    public function getArgs(array $params)
    {
        // Build DSN string
        $dsn = sprintf('pgsql:dbname=%s;host=%s;user=%s;password=%s', $params['dbname'], $params['host'], $params['user'], $params['password']);

        return array($dsn, null, null, array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => true,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ));
    }
}

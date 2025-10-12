<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Sql;

interface SqlDbConnectorFactoryInterface
{
    /**
     * Builds database service instance
     * 
     * @param string $vendor Database vendor name
     * @param array $options Options for connection, such as username and password
     * @throws \RuntimeException If unknown vendor name supplied
     * @return \Krystal\Db\Sql\DbInterface
     */
    public function build($vendor, array $options);
}

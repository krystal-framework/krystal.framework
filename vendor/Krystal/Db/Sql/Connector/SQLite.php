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

final class SQLite implements ConnectorInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getArgs(array $config)
	{
		$dsn = 'sqlite:'.$config['path'];

		if (isset($config['dbname'])) {
			$dsn .= ';dbname='.$config['dbname'];
		}

		return array($dsn, null, null, array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_EMULATE_PREPARES => true,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
		));
	}
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Sql\Connector;

use PDO;

final class PostgreSQL extends PDO
{
	/**
	 * Initializes the PDO for PostgreSQL
	 * 
	 * @param array $params
	 * @return void
	 */
	public function __construct(array $params)
	{
		// Build DSN string
		$dsn = sprintf('pgsql:dbname=%s;host=%s;user=%s;password=%s', $params['dbname'], $params['host'], $params['user'], $params['password']);

		parent::__construct($dsn);

		$attrs = array(
			parent::ATTR_ERRMODE => parent::ERRMODE_EXCEPTION,
			parent::ATTR_EMULATE_PREPARES => true,
			parent::ATTR_DEFAULT_FETCH_MODE => parent::FETCH_ASSOC,
		);

		// Set attributes now
		foreach ($attrs as $attr => $value) {
			$this->setAttribute($attr, $value);
		}
	}
}

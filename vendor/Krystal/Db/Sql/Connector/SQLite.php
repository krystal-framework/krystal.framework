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

final class SQLite extends PDO
{
	/**
	 * Initializes the PDO for SQLite
	 * 
	 * @param array $params
	 * @return void
	 */
	public function __construct(array $params)
	{
		$dsn = 'sqlite:'.$params['path'];

		if (isset($params['dbname'])) {
			$dsn .= ';dbname='.$params['dbname'];
		}

		parent::__construct($dsn);

		$attrs = array(
			parent::ATTR_ERRMODE => parent::ERRMODE_EXCEPTION,
			parent::ATTR_EMULATE_PREPARES => true,
			parent::ATTR_DEFAULT_FETCH_MODE => parent::FETCH_ASSOC
		);

		foreach ($attrs as $attr => $value) {
			$this->setAttribute($attr, $value);
		}
	}
}

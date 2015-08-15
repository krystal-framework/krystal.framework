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

final class MySQL extends PDO
{
	/**
	 * Initializes the PDO for MySQL
	 * 
	 * @param array $params
	 * @return void
	 */
	public function __construct(array $params)
	{
		$dsn = 'mysql:host='.$params['host'];

		if (isset($params['dbname'])) {
			$dsn .= ';dbname='.$params['dbname'];
		}

		$attrs = array(
			parent::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8',
			parent::ATTR_ERRMODE => parent::ERRMODE_EXCEPTION,
			parent::ATTR_EMULATE_PREPARES => true,
			parent::ATTR_DEFAULT_FETCH_MODE => parent::FETCH_ASSOC,
		);

		parent::__construct($dsn, $params['username'], $params['password'], $attrs);
	}
}

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

interface ConnectorInterface
{
	/**
	 * Returns arguments to be passed to PDO
	 * 
	 * @param array $config Configuration data
	 * @return array
	 */
	public function getArgs(array $config);
}

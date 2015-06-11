<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Sql;

interface QueryObjectInterface
{
	/**
	 * Returns current query string
	 * 
	 * @return string
	 */
	public function getQueryString();

	/**
	 * Clears the query string
	 * 
	 * @return \Krystal\Db\Sql\QueryBuilder
	 */
	public function clear();
}

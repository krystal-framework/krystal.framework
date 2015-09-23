<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Sql\Relations;

use Krystal\Db\Sql\DbInterface;

abstract class AbstractRelation
{
	/**
	 * Database service
	 * 
	 * @var \Krystal\Db\Sql\DbInterface
	 */
	protected $db;

	/**
	 * State initialization
	 * 
	 * @param \Krystal\Db\Sql\DbInterface $db Database service
	 * @return void
	 */
	public function __construct(DbInterface $db)
	{
		$this->db = $db;
	}
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Sql\Relations;

final class OneToOne extends AbstractRelation
{
	/**
	 * Merges with main result-set
	 * 
	 * @param array $rows
	 * @param string $column Column name from the master table to be removed
	 * @param string $alias Virtual column name to be appeared instead of removed one
	 * @param string Slave table name
	 * @return array
	 */
	public function merge(array $rows, $column, $alias, $table, $link)
	{
		foreach ($rows as &$row) {
			if (isset($row[$column])) {
				$value = $row[$column];

				$set = $this->querySlaveTable($table, $link, $value);
				$set = $this->getPreparedSet($set, $link);

				$row[$alias] = $set;
				unset($row[$column]);
			}
		}

		return $rows;
	}

	/**
	 * Queries slave table
	 * 
	 * @param string $table Slave table name
	 * @param string $link Linking column name to be selected
	 * @param string $column Slave column id name
	 * @param string $value Value of PK from master table
	 * @return array
	 */
	private function querySlaveTable($table, $column, $value)
	{
		return $this->db->select('*')
						->from($table)
						->whereEquals($column, $value)
						->getStmt()
						->fetch();
	}

	/**
	 * Returns a result-set excluding slave column id name
	 * 
	 * @param array $row Raw row
	 * @param string $id Column name to be excluded
	 * @return array
	 */
	private function getPreparedSet(array $row, $id)
	{
		// Make sure the id exists
		if (isset($row[$id])) {
			unset($row[$id]);
		}

		return $row;
	}
}

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

final class OneToMany extends AbstractRelation
{
	/**
	 * Merges with main result-set
	 * 
	 * @param array $rows
	 * @param string $alias
	 * @param string $slaveTable
	 * @param string $slaveColumnId
	 * @return array
	 */
	public function merge($pk, array $rows, $alias, $slaveTable, $slaveColumnId)
	{
		foreach ($rows as &$row) {
			// Extra check
			if (isset($row[$pk])) {

				$value = $row[$pk];

				// Prepare slave set
				$slaveSet = $this->querySlaveTable($slaveTable, $slaveColumnId, $value);
				$slaveSet = $this->getResultSetWithoutSlaveColumnId($slaveSet, $slaveColumnId);

				$row[$alias] = $slaveSet;
			}
		}

		return $rows;
	}

	/**
	 * Queries slave table
	 * 
	 * @param string $slaveTable Slave table name
	 * @param string $slave Slave column id name
	 * @param string $value Value of PK from master table
	 * @return array
	 */
	private function querySlaveTable($slaveTable, $slaveColumnId, $value)
	{
		return $this->db->select('*')
						->from($slaveTable)
						->whereEquals($slaveColumnId, $value)
						->getStmt()
						->fetchAll();
	}

	/**
	 * Returns a result-set excluding slave column id name
	 * 
	 * @param array $rows Raw result-set
	 * @param string $slaveColumnId Slave column id name to be excluded
	 * @return array
	 */
	private function getResultSetWithoutSlaveColumnId(array $rows, $slaveColumnId)
	{
		// To be returned
		$result = array();

		foreach ($rows as $row) {
			// Make sure the name is valid
			if (isset($row[$slaveColumnId])) {
				unset($row[$slaveColumnId]);
			}

			// Append filtered $row array to the outputting array
			$result[] = $row;
		}

		return $result;
	}
}

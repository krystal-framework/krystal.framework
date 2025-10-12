<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Sql\Relations;

final class ManyToMany extends AbstractRelation
{
    /**
     * Merges as many-to-many
     * 
     * @param string $masterPk Primary key name from the master table
     * @param string $alias Virtual column to be appended
     * @param string $junction Junction table name
     * @param string $column Column name from junction table to be selected
     * @param string $table Slave table name table
     * @param string $pk PK column name in slave table
     * @return array
     */
    public function merge($masterPk, $alias, array $rows, $junction, $column, $table, $pk, $columns)
    {
        foreach ($rows as &$row) {
            $value = $row[$masterPk];
            $row[$alias] = $this->getSlaveData($table, $pk, $junction, $column, $value, $columns);
        }

        return $rows;
    }

    /**
     * Returns slave data from associated table
     * 
     * @param string $slaveTable Slave (second) table name
     * @param string $slavePk Primary column name in slave table
     * @param string $junction Junction table name
     * @param string $column Column in junction table to be queried
     * @param string $value Value for the column being queried
     * @param mixed $columns Columns to be selected in slave table
     * @return array
     */
    private function getSlaveData($slaveTable, $slavePk, $junction, $column, $value, $columns)
    {
        $ids = $this->queryJunctionTable($junction, $column, $value, '*');

        if (empty($ids)) {
            return array();
        } else {
            // Query only in case there attached IDs found
            return $this->queryTable($slaveTable, $slavePk, $ids, $columns);
        }
    }
    
    /**
     * Extracts only values. It's like array_values() supporting nested arrays
     * 
     * @param array $rows
     * @return array
     */
    private function extractValues(array $rows)
    {
        $result = array();

        foreach ($rows as $row) {
            foreach ($row as $column => $value) {
                array_push($result, $value);
            }
        }

        return $result;
    }

    /**
     * Removes all columns leaving only target one
     * 
     * @param array $row Current row
     * @param string $target Current column name
     * @return array
     */
    private function leaveOnlyCurrent(array $row, $target)
    {
        $result = array();

        foreach ($row as $column => $value) {
            if ($column != $target) {
                $result[$column] = $value;
            }
        }

        return $result;
    }

    /**
     * Queries junction table
     * 
     * @param string $table Junction table name
     * @param string $column
     * @param string $value
     * @param mixed $columns Columns to be selected in slave table
     * @return array
     */
    private function queryJunctionTable($table, $column, $value, $columns)
    {
        $rows = $this->queryTable($table, $column, array($value), $columns);

        foreach ($rows as &$row) {
            $row = $this->leaveOnlyCurrent($row, $column);
        }

        return $this->extractValues($rows);
    }

    /**
     * Queries slave table
     * 
     * @param string $table Slave table name
     * @param string $link Linking column name to be selected
     * @param string $column Slave column id name
     * @param array $values Value of PK from master table
     * @param mixed $columns Columns to be selected in slave table
     * @return array
     */
    private function queryTable($table, $column, array $values, $columns)
    {
        return $this->db->select($columns)
                        ->from($table)
                        ->whereIn($column, $values)
                        ->getStmt()
                        ->fetchAll();
    }
}

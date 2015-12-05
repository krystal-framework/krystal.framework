<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Db\Sql;

final class TableDumper implements TableDumperInterface
{
    /**
     * Prepared PDO instance
     * 
     * @var \PDO
     */
    protected $pdo;

    /**
     * State initialization
     * 
     * @param \PDO $pdo
     * @return void
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Fetch all tables
     * 
     * @return array
     */
    public function fetchAllTables()
    {
        $tables = array();
        $result = $this->pdo->query('SHOW TABLES')->fetchAll();

        foreach ($result as $index => $array) {
            // Extract a value - we don't care about a key
            $data = array_values($array);
            // Its ready not, just append it
            $tables[] = $data[0];
        }

        return $tables;
    }

    /**
     * Dumps into SQL string
     * 
     * @param array $tables
     * @return string
     */
    public function dump(array $tables = array())
    {
        if (empty($tables)) {
            $tables = $this->fetchAllTables();
        }

        $result = null;

        // Building logic
        foreach ($tables as $table) {

            $stmt = $this->pdo->query(sprintf('SELECT * FROM `%s`', $table));
            $fieldCount = $stmt->columnCount();

            // Append additional drop state
            $result .=  sprintf('DROP TABLE IF EXISTS `%s`;', $table);

            // Show how this table was created
            $createResult = $this->pdo->query(sprintf('SHOW CREATE TABLE %s', $table))->fetch();
            $result .=  "\n\n" . $createResult['Create Table'] . ";\n\n";

            // Start main loop
            for ($i = 0; $i < $fieldCount; $i++) {
                while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                    
                    // Start appending INSERT statement
                    $result .= sprintf('INSERT INTO `%s` VALUES (', $table);
                    
                    for ($j = 0; $j < $fieldCount; $j++) {
                        
                        // We need to ensure all quotes are properly escaped
                        $row[$j] = addslashes($row[$j]);
                        
                        // Ensure its correctly escaped
                        $row[$j] = str_replace("\n", "\\n", $row[$j]);
                        
                        if (isset($row[$j])) {
                            $result .= sprintf('"%s"', $row[$j]);
                        } else {
                            $result .= '""'; 
                        }
                        
                        if ($j < ($fieldCount - 1)) {
                            $result .= ','; 
                        }
                    }
                    
                    $result .= ");\n";
                }
            }
            
            $result .= "\n\n\n";
        }
        
        return $result;
    }
}

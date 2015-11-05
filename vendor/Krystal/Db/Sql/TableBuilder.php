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

use InvalidArgumentException;

final class TableBuilder implements TableBuilderInterface
{
    /**
     * PDO instance
     * 
     * @var \PDO
     */
    private $pdo;

    /**
     * A query to be executed
     * 
     * @var string
     */
    private $query;

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
     * Loads data from a string
     * 
     * @param string $content
     * @return void
     */
    public function loadFromString($content)
    {
        $this->query = $content;
    }

    /**
     * Loads data from file
     * 
     * @param string $filename
     * @return boolean
     */
    public function loadFromFile($filename)
    {
        if (is_file($filename)) {
            $content = file_get_contents($filename);
            $this->loadFromString($content);

            return true;
        } else {
            return false;
        }
    }

    /**
     * Build tables
     * 
     * @return boolean
     */
    public function run()
    {
        return $this->pdo->exec($this->query);
    }
}

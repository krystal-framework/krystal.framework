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

interface TableBuilderInterface
{
    /**
     * Loads data from a string
     * 
     * @param string $content
     * @return void
     */
    public function loadFromString($content);

    /**
     * Loads data from file
     * 
     * @param string $filename
     * @return boolean
     */
    public function loadFromFile($filename);

    /**
     * Build tables
     * 
     * @return boolean Depending on success
     */
    public function run();
}
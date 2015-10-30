<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Config\Sql;

interface ConfigMapperInterface
{
    /**
     * Fetches all configuration data
     * 
     * @return array
     */
    public function fetchAll();

    /**
     * Truncates a repository
     * 
     * @return boolean
     */
    public function truncate();

    /**
     * Inserts a new record
     * 
     * @param string $module
     * @param string $name
     * @param mixed $value
     * @return boolean
     */
    public function insert($module, $name, $value);

    /**
     * Updates a record
     * 
     * @param string $module
     * @param string $name
     * @param mixed $value
     * @return boolean
     */
    public function update($module, $name, $value);

    /**
     * Deletes all configuration data by associated module
     * 
     * @param string $module
     * @return boolean
     */
    public function deleteAllByModule($module);

    /**
     * Deletes a record
     * 
     * @param string $module
     * @param string $name
     * @return boolean
     */
    public function delete($module, $name);
}

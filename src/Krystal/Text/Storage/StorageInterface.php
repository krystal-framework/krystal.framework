<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Text\Storage;

interface StorageInterface
{
    /**
     * Loads data from a storage
     * 
     * @return array Returns loaded data
     */
    public function load();

    /**
     * Saves data into a storage
     * 
     * @param array $data Data to be saved
     * @return void
     */
    public function save(array $data);

    /**
     * Clears data from a storage
     * 
     * @return void
     */
    public function clear();
}

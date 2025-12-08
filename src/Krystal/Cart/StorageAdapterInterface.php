<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Cart;

/**
 * Interface for cart storage adapters
 */
interface StorageAdapterInterface
{
    /**
     * Save cart data
     * 
     * @param array $data Cart data
     */
    public function save(array $data);

    /**
     * Load cart data
     * 
     * @return array Cart data
     */
    public function load();
    
    /**
     * Clear cart data
     */
    public function clear();
}

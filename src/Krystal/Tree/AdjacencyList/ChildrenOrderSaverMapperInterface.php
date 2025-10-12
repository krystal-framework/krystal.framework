<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Tree\AdjacencyList;

interface ChildrenOrderSaverMapperInterface
{
    /**
     * Saves new order of items
     * 
     * @param string $id Target id
     * @param string $parentId
     * @param integer $range New range
     * @return boolean
     */
    public function save($id, $parentId, $range);
}

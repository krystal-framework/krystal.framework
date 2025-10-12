<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Tree\AdjacencyList;

interface RelationBuilderInterface
{
    /**
     * Builds a relational tree
     * 
     * @param array $data Raw data
     * @return array
     */
    public function build(array $data);
}

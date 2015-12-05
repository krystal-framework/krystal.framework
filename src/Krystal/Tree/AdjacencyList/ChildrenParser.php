<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Tree\AdjacencyList;

class ChildrenParser
{
    /**
     * Children key
     * 
     * @var string
     */
    protected $childrenKey;

    /**
     * State initialization
     * 
     * @param string $childrenKey
     * @return void
     */
    public function __construct($childrenKey = 'children')
    {
        $this->childrenKey = $childrenKey;
    }

    /**
     * Parses nested array with children
     * 
     * @param array $data
     * @param string|integer $parentId
     * @return array
     */
    final protected function parseData(array $data, $parentId = 0)
    {
        $result = array();

        foreach ($data as $subArray) {
            $nested = array();

            if (isset($subArray[$this->childrenKey])) {
                // Recursive call
                $nested = $this->parseData($subArray[$this->childrenKey], $subArray[RelationBuilder::TREE_PARAM_ID]);
            }

            $result[] = array(
                RelationBuilder::TREE_PARAM_ID => $subArray[RelationBuilder::TREE_PARAM_ID], 
                RelationBuilder::TREE_PARAM_PARENT_ID => $parentId
            );

            $result = array_merge($result, $nested);
        }

        return $result;
    }
}

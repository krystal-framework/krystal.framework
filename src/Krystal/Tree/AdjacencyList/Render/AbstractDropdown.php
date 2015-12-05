<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Tree\AdjacencyList\Render;

use Krystal\Tree\AdjacencyList\RelationBuilder;

abstract class AbstractDropdown extends AbstractRenderer
{
    /**
     * Recursive level calls
     * 
     * @var integer
     */
    protected $level = 1;

    /**
     * Gets called by recursion when creating a list item
     * 
     * @param array $row
     * @param array $parents
     * @param string $active Active page if needed
     * @return string
     */
    abstract protected function getChildOpener(array $row, array $parents, $active);

	/**
     * Gets called when closing a parent element inside recursion
     * 
     * @return string
     */
    abstract protected function getParentCloser();

	/**
     * Gets called when recursion closes a child element
     * 
     * @return string
     */
    abstract protected function getChildCloser();

	/**
     * Gets called when recursion creates a first level parent element
     * 
     * @return string
     */
    abstract protected function getFirstLevelParent();

    /**
     * Gets called when recursion creates a nested parent element
     * 
     * @return string
     */
    abstract protected function getNestedLevelParent();

    /**
     * {@inheritDoc}
     */
    public function render(array $menuData, $active = false, $parentId = 0)
    {
        // HTML fragment
        $fragment = '';

        // Determine whether we have it in relationships
        if (isset($menuData[RelationBuilder::TREE_PARAM_PARENTS][$parentId])) {
            // If that's just a first level, then gotta check if we need to append a class
            if ($this->level == 1) {
                $fragment = $this->getFirstLevelParent() . PHP_EOL;
            } else {
                // It's not a parent of first level
                $fragment = $this->getNestedLevelParent() . PHP_EOL;
            }

            foreach ($menuData[RelationBuilder::TREE_PARAM_PARENTS][$parentId] as $itemId) {
                // Target row
                $row = $menuData[RelationBuilder::TREE_PARAM_ITEMS][$itemId];

                $fragment .= $this->getChildOpener($row, $menuData[RelationBuilder::TREE_PARAM_PARENTS], $active) . PHP_EOL;

                $this->level++;

                //Recursion starts here 
                $fragment .= call_user_func(__METHOD__, $menuData, $active, $itemId);
                $fragment .= $this->getChildCloser() . PHP_EOL;
            }

            $fragment .= $this->getParentCloser() . PHP_EOL;
        }

        return $fragment;
    }
}

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
use RuntimeException;

final class PhpArray extends AbstractRenderer
{
    /**
     * Target separator
     * 
     * @var string
     */
    private $separator;

    /**
     * Indicates level of recursive call
     * 
     * @var integer
     */
    private $level = 1;

    /**
     * Column to be used as title
     * 
     * @var string
     */
    private $column;

    /**
     * State initialization
     * 
     * @param string $column Title column
     * @param string $separator
     * @return void
     */
    public function __construct($column, $separator = '—')
    {
        $this->column = $column;
        $this->separator = $separator;
    }

    /**
     * {@inheritDoc}
     */
    public function render(array $data, $active = null, $parentId = 0)
    {
        $result = array();

        if (isset($data[RelationBuilder::TREE_PARAM_PARENTS][$parentId])) {
            foreach ($data[RelationBuilder::TREE_PARAM_PARENTS][$parentId] as $itemId) {
                $row = $data[RelationBuilder::TREE_PARAM_ITEMS][$itemId];

                // Make sure first, that valid column name is provided
                if (!isset($row[$this->column])) {
                    throw new RuntimeException(sprintf('Missing defined column name in collection "%s"', $this->column));
                }

                // That's array's value
                $value = sprintf('%s %s', str_repeat($this->separator, $this->level - 1), $row[$this->column]);
                $result[$row[RelationBuilder::TREE_PARAM_ID]] = $value;

                // subsequent items will be indented one level
                $this->level++;

                // Recursive call
                $result = ($result + $this->render($data, $active, $itemId));

                // recursive call has returned, so restore a level
                $this->level--;
            }
        }

        return $result;
    }
}

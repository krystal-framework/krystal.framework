<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Grid;

final class Grid
{
    /**
     * Renders the grid
     * 
     * @param array $data
     * @param array $options
     * @return string
     */
    public static function render(array $data, array $options)
    {
        $maker = new TableMaker($data, $options);
        return $maker->render();
    }
}

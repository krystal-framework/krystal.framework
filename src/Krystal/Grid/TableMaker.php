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

final class TableMaker
{
    /**
     * Table data
     * 
     * @var array
     */
    private $data = array();

    /**
     * State initialization
     * 
     * @param array $data
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }
}

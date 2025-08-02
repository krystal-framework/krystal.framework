<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Paginate\Style;

final class SlideStyle implements StyleInterface
{
    /**
     * Amount of pages
     * 
     * @var integer
     */
    private $step;

    /**
     * State initialization
     * 
     * @param integer $step
     * @return void
     */
    public function __construct($step)
    {
        $this->step = $step;
    }

    /**
     * Filter page numbers via current style adapter
     * 
     * @param array $page Array of page numbers
     * @param integer $current Current page number
     * @return array
     */
    public function getPageNumbers(array $pages, $current)
    {
        $offset = $current * $this->step;
        return array_slice($pages, $offset, $this->step);
    }
}

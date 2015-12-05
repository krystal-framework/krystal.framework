<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Form\Gadget;

interface DataSorterInterface
{
    /**
     * Returns sorting options
     * 
     * @return array
     */
    public function getSortingOptions();

    /**
     * Returns current sort option
     * 
     * @return string
     */
    public function getSortOption();

    /**
     * Stores sorting option
     * 
     * @param string $sort
     * @return boolean
     */
    public function setSortOption($sort);
}

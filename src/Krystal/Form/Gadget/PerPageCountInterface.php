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

interface PerPageCountInterface
{
    /**
     * Returns per page count options
     * 
     * @return array Array of option entities
     */
    public function getPerPageCountValues();

    /**
     * Returns current per page count
     * 
     * @return integer
     */
    public function getPerPageCount();

    /**
     * Defines new per page count
     * 
     * @param integer $count Current count
     * @return boolean
     */
    public function setPerPageCount($count);
}

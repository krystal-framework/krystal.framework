<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Date;

interface ZodiacalInterface
{
    /**
     * Get zodiac sign for current date
     * 
     * @return string
     */
    public function getSign();

    /**
     * Get all available zodiac signs
     * 
     * @return array
     */
    public function getAvailableSigns();
}

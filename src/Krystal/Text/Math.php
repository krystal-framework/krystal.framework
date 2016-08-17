<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Text;

class Math
{
    /**
     * Counts a percentage
     * 
     * @param float|integer $total
     * @param float|integer $actual
     * @param integer 
     * @return mixed
     */
    public static function percentage($total, $actual, $round = 1)
    {
        $value = 100 * $actual / $total;

        if (is_integer($round)) {
            $value = round($value, $round);
        }

        return $value;
    }
}

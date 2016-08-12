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

/**
 * Time related helper methods 
 */
abstract class TimeHelper
{
    const MINUTE = 60; 
    const SECOND = 1;
    const DAY = 86400;
    const WEEK = 604800;
    const MONTH = 2592000;
    const YEAR = 31536000;

    /**
     * Create years
     * 
     * @param integer $start Starting year
     * @param integer $end Ending year
     * @return array
     */
    public static function createYears($start, $end)
    {
        $result = array();

        for ($i = $start; $i < $end + 1; $i++) {
            $result[$i] = $i;
        }

        return $result;
    }

    /**
     * Create years up to current one
     * 
     * @param integer $start Starting year
     * @param integer $end Ending year
     * @return array
     */
    public static function createYearsUpToCurrent($start)
    {
        return self::createYears($start, date('Y'));
    }
}

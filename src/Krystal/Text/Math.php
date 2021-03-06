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
     * An equivalent to Excel's precise function
     * 
     * @param float The value to be rounded
     * @param int $significance The multiple to which number is to be rounded
     * @return mixed
     */
    public static function ceiling($number, $significance = 1)
    {
        return (ceil($number / $significance) * $significance);
    }

    /**
     * Formats a number without rounding it, unlike built-in function number_format()
     * 
     * @param float $number The number being formatted
     * @@param int $decimals The number of decimal points.
     * @param string $dec_point Separator for the decimal point.
     * @param string $thousands_sep Thousands separator
     * @return string
     */
    public static function numberFormat($number, $decimals = 2, $dec_point = '.', $thousands_sep  = ',')
    {
        $number = bcdiv($number, 1, $decimals);
        return number_format($number, $decimals, $dec_point, $thousands_sep);
    }

    /**
     * Rounds a collection
     * 
     * @param array $data
     * @param integer $precision
     * @return array
     */
    public static function roundCollection(array $data, $precision = 2)
    {
        $output = array();

        foreach ($data as $key => $value){
            $output[$key] = round($value, $precision);
        }

        return $output;
    }

    /**
     * Finds the average
     * 
     * @param array $values
     * @return float
     */
    public static function average($values)
    {
        $sum = array_sum($values);
        $count = count($values);

        // Avoid useless calculations
        if ($count == 0) {
            return 0;
        }

        return $sum / $count;
    }

    /**
     * Returns discount price (useful when calculating discount price)
     * 
     * @param float $target Initial price
     * @param float $discount
     * @return float
     */
    public static function getDiscount($price, $discount)
    {
        return $price - self::fromPercentage($price, $discount);
    }

    /**
     * Counts value from percentage
     * 
     * @param float $target Target value
     * @param float $percentage Target percentage
     * @return float
     */ 
    public static function fromPercentage($target, $percentage)
    {
        $result = $target * $percentage / 100;
        return round($result, 2);
    }

    /**
     * Counts a percentage
     * 
     * @param float|integer $total
     * @param float|integer $actual
     * @param integer $round 
     * @return mixed
     */
    public static function percentage($total, $actual, $round = 1)
    {
        // Avoid useless calculations
        if ($total == 0 || $actual == 0) {
            return 0;
        }

        $value = 100 * $actual / $total;

        if (is_integer($round)) {
            $value = round($value, $round);
        }

        return $value;
    }
}

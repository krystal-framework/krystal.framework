<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Text;

class Math
{
    /**
     * Rounds a number up to the nearest multiple of a given significance.
     * An equivalent to Excel's precise function
     * 
     * @param float|int $number The number to round up.
     * @param float|int $significance The significance (multiple) to round up to. Defaults to 1.
     *
     * @return float|int The rounded number.
     */
    public static function ceiling($number, $significance = 1)
    {
        if (!is_numeric($number) || !is_numeric($significance) || $significance == 0) {
            return 0;
        }

        return (ceil($number / $significance) * $significance);
    }

    /**
     * Formats a number without rounding it, unlike PHP's built-in number_format().
     *
     * @param float|int $number The number being formatted.
     * @param int $decimals Number of decimal places.
     * @param string $dec_point Separator for the decimal point.
     * @param string $thousands_sep Thousands separator.
     *
     * @return string Formatted number as a string.
     */
    public static function numberFormat($number, $decimals = 2, $dec_point = '.', $thousands_sep  = ',')
    {
        $number = bcdiv($number, 1, $decimals);
        return number_format($number, $decimals, $dec_point, $thousands_sep);
    }

    /**
     * Rounds all numeric values in a collection to the given precision.
     *
     * @param array $data The input array with numeric values.
     * @param int $precision Number of decimal places. Defaults to 2.
     *
     * @return array The array with rounded values.
     */
    public static function roundCollection(array $data, $precision = 2)
    {
        $output = array();

        foreach ($data as $key => $value){
            $output[$key] = is_numeric($value) ? round($value, $precision) : $value;
        }

        return $output;
    }

    /**
     * Finds the arithmetic average (mean) of values in an array.
     *
     * @param array $values A collection of numeric values.
     * @return float The average value. Returns 0 if the array is empty or contains no numeric values.
     */
    public static function average($values)
    {
        if (!is_array($values) || empty($values)) {
            return 0.0;
        }

        $sum = array_sum($values);
        $count = count($values);

        return $count > 0 ? $sum / $count : 0.0;
    }

    /**
     * Returns the discounted price after applying a percentage discount.
     *
     * @param float|int $price Initial price before discount.
     * @param float|int $discount Discount percentage (e.g. 20 = 20%).
     * @return float Final price after discount.
     */
    public static function getDiscount($price, $discount)
    {
        return $price - self::fromPercentage($price, $discount);
    }

    /**
     * Calculates the value from a percentage of a target.
     *
     * @param float|int $target The base target value.
     * @param float|int $percentage The percentage to apply.
     * @return float The calculated value, rounded to 2 decimals.
     */
    public static function fromPercentage($target, $percentage)
    {
        if (!is_numeric($target) || !is_numeric($percentage)) {
            return 0.0;
        }

        $result = ($target * $percentage) / 100;
        return round($result, 2);
    }

    /**
     * Calculates what percentage the actual value is of the total.
     *
     * @param float|int $total  The total or maximum value.
     * @param float|int $actual The actual (part) value.
     * @param int $round  Number of decimal places to round to. Defaults to 1.
     * @return float The calculated percentage. Returns 0 if $total or $actual is 0.
     */
    public static function percentage($total, $actual, $round = 1)
    {
        if (!is_numeric($total) || !is_numeric($actual) || $total == 0) {
            return 0.0;
        }

        $value = (100 * $actual) / $total;

        return is_int($round) ? round($value, $round) : $value;
    }
}

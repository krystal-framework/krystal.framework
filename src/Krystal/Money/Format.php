<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Money;

final class Format
{
    /**
     * Formats amount with its currency
     * 
     * @param float|int $amount Numeric amount to be formatted
     * @param string $currency
     * @param boolean $ltr Whether to output currency after price or not
     * @return string
     */
    public static function price($amount, $currency, $ltr = true)
    {
        $separator = ' ';
        $price = number_format($amount);

        if ($ltr) {
            return $price . $separator . $currency;
        } else {
            return $currency . $separator . $price;
        }
    }
}

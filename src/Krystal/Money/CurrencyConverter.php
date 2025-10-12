<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Money;

use RuntimeException;

final class CurrencyConverter implements CurrencyConverterInterface
{
    /**
     * Base value
     * 
     * @var float
     */
    private $base = 0;

    /**
     * Registered rates
     * 
     * @var array
     */
    private $rates = array();

    /**
     * State initialization
     * 
     * @param array $rates
     * @return void
     */
    public function __construct(array $rates)
    {
        $this->rates = $rates;
    }

    /**
     * Checks whether the given currency exists in the rate map.
     * 
     * This method is useful for validating before calling {@see set()} 
     * or {@see convert()} to prevent runtime exceptions.
     * 
     * @param string $currency The currency code to check
     * @return bool True if the currency is available, false otherwise
     */
    public function isAvailable($currency)
    {
        return isset($this->rates[$currency]);
    }

    /**
     * Converts the normalized base amount into the target currency.
     * 
     * The base amount is set via {@see set()} and internally stored in 
     * its USD-equivalent form. This method looks up the target currency’s 
     * rate in the map, converts the base into that currency, and returns 
     * the result rounded to 2 decimal places.
     * 
     * @param string $currency The target currency code
     * 
     * @throws \RuntimeException If the currency does not exist in the rate map
     * @return float Converted amount in the target currency
     */
    public function convert($currency)
    {
        if ($this->isAvailable($currency)) {
            $rate = 1 / $this->rates[$currency];
            return round($this->base * $rate, 2);
        } else {
            throw new RuntimeException(sprintf(
                'Target currency "%s" does not belong to the registered map of rates', $currency
            ));
        }
    }

    /**
     * Defines the base amount for conversions.
     * 
     * This method sets the internal base value by converting the given amount 
     * from the specified currency into its USD-equivalent, using the rate map 
     * defined at initialization. All subsequent calls to {@see convert()} 
     * will use this normalized base for calculations.
     * 
     * @param float  $amount   The amount of money in the specified currency
     * @param string $currency The currency code of the amount (must exist in the rate map)
     * 
     * @throws \RuntimeException If the specified currency is not available in the rates map
     * @return void
     */
    public function set($amount, $currency)
    {
        if ($this->isAvailable($currency)) {
            $this->base = $amount * $this->rates[$currency];
        } else {
            throw new RuntimeException(sprintf(
                'Can not set main rate "%s" that do not belong to initial collection of rates', $currency
            ));
        }
    }
}

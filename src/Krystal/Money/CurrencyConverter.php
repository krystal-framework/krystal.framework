<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
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
     * Checks whether a currency exists in the map
     * 
     * @param string $currency
     * @return boolean
     */
    public function isAvailable($currency)
    {
        return isset($this->rates[$currency]);
    }

    /**
     * Returns converted value
     * 
     * @param string $currency
     * @throws \RuntimeException if target currency doesn't belong to the map of rates
     * @return float
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
     * Sets/overrides main currency
     * 
     * @param float $amount
     * @param string $currency
     * @throws \RuntimeException if main currency is out of the list
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

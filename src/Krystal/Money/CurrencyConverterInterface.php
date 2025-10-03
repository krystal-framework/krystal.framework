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

interface CurrencyConverterInterface
{
    /**
     * Checks whether a currency exists in the map
     * 
     * @param string $currency
     * @return boolean
     */
    public function isAvailable($currency);

    /**
     * Returns converted value
     * 
     * @param string $currency
     * @throws \RuntimeException if target currency doesn't belong to the map of rates
     * @return float
     */
    public function convert($currency);

    /**
     * Sets/overrides main currency
     * 
     * @param float $amount
     * @param string $currency
     * @throws \RuntimeException if main currency is out of the list
     * @return void
     */
    public function set($amount, $currency);
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Cart;

/**
 * Product domain model
 */
final class Product
{
    /** @var string */
    private $id;

    /** @var float|null */
    private $price;

    /**
     * State initialization
     * 
     * @param string $id Product identifier
     * @param float|null $price Optional price
     */
    public function __construct($id, $price = null)
    {
        $this->id = $id;
        $this->price = $price;
    }

    /**
     * Get product ID
     * 
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get product price
     * 
     * @return float|null
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set product price
     * 
     * @param float|null $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Convert to array
     * 
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'price' => $this->price
        ];
    }
}

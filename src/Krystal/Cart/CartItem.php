<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Cart;

/**
 * Shopping cart item
 */
final class CartItem
{
    /** @var \Krystal\Cart\Product */
    private $product;

    /** @var int */
    private $quantity;

    /** @var array */
    private $attributes;

    /**
     * State initialization
     * 
     * @param \Krystal\Cart\Product $product Product object
     * @param int $quantity Item quantity
     * @param array $attributes Product attributes
     */
    public function __construct(Product $product, $quantity = 1, array $attributes = [])
    {
        $this->product = $product;
        $this->quantity = $quantity;
        $this->attributes = $attributes;
    }

    /**
     * Get product
     * 
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Get product ID
     * 
     * @return string
     */
    public function getProductId()
    {
        return $this->product->getId();
    }

    /**
     * Get quantity
     * 
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set quantity
     * 
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * Increase quantity
     * 
     * @param int $amount Amount to increase
     */
    public function increaseQuantity($amount = 1)
    {
        $this->quantity += $amount;
    }

    /**
     * Decrease quantity
     * 
     * @param int $amount Amount to decrease
     */
    public function decreaseQuantity($amount = 1)
    {
        $this->quantity = max(0, $this->quantity - $amount);
    }

    /**
     * Get attributes
     * 
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set attributes
     * 
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Get item price
     * 
     * @return float|null
     */
    public function getPrice()
    {
        return $this->product->getPrice();
    }

    /**
     * Get total price for this item
     * 
     * @return float|null
     */
    public function getTotal()
    {
        $price = $this->getPrice();
        return $price !== null ? $price * $this->quantity : null;
    }

    /**
     * Check if this item matches another product with attributes
     * 
     * @param string $productId Product ID
     * @param array $attributes Product attributes
     * @return bool
     */
    public function matches($productId, array $attributes = [])
    {
        if ($this->getProductId() !== $productId) {
            return false;
        }

        return $this->attributesMatch($attributes);
    }

    /**
     * Check if attributes match
     * 
     * @param array $attributes Attributes to compare
     * @return bool
     */
    private function attributesMatch(array $attributes)
    {
        return $this->attributes == $attributes;
    }

    /**
     * Get attribute value
     * 
     * @param string $key Attribute key
     * @param mixed $default Default value if attribute doesn't exist
     * @return mixed Attribute value or default
     */
    public function getAttribute($key, $default = null)
    {
        return isset($this->attributes[$key]) ? $this->attributes[$key] : $default;
    }

    /**
     * Check if item has specific attribute
     * 
     * @param string $key Attribute key
     * @return bool True if attribute exists
     */
    public function hasAttribute($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * Convert to array
     * 
     * @return array
     */
    public function toArray()
    {
        return [
            'productId' => $this->getProductId(),
            'quantity' => $this->quantity,
            'price' => $this->getPrice(),
            'attributes' => $this->attributes,
            'total' => $this->getTotal()
        ];
    }
}

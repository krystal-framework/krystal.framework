<?php

/**
 * This file is part of the Krystal Framework
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Cart;

/**
 * Main shopping cart class
 */
final class ShoppingCart
{
    /** @var \Krystal\Cart\StorageAdapterInterface */
    private $storage;

    /** @var \Krystal\Cart\CartItem[] */
    private $items = [];

    /**
     * State initialization
     * 
     * @param \Krystal\Cart\StorageAdapterInterface $storage Storage adapter
     */
    public function __construct(StorageAdapterInterface $storage)
    {
        $this->storage = $storage;
        $this->load();
    }

    /**
     * Add item to cart or update existing one
     * 
     * @param string $productId Product ID
     * @param int $quantity Quantity
     * @param array $attributes Product attributes
     * @param float|null $price Optional price
     * @return bool Success status
     */
    public function add($productId, $quantity = 1, array $attributes = [], $price = null)
    {
        if ($quantity <= 0) {
            return false;
        }

        // Check if item with same ID and attributes already exists
        $existingItemKey = $this->findItemKey($productId, $attributes);

        if ($existingItemKey !== null) {
            // Update existing item
            $this->items[$existingItemKey]->increaseQuantity($quantity);

            // Update price if provided
            if ($price !== null) {
                $this->items[$existingItemKey]->getProduct()->setPrice($price);
            }

            $this->save();
            return true;
        }

        // Create new item
        $product = new Product($productId, $price);
        $item = new CartItem($product, $quantity, $attributes);
        $key = $this->generateItemKey($productId, $attributes);
        $this->items[$key] = $item;

        $this->save();
        return true;
    }

    /**
     * Update item by product ID and attributes
     * 
     * @param string $productId Product ID
     * @param array $attributes Attributes to identify item (empty array for no attributes)
     * @param array $updates Array of updates ['quantity' => ?, 'newAttributes' => ?, 'price' => ?]
     * @return bool Success status
     */
    public function update($productId, array $attributes, array $updates)
    {
        $itemKey = $this->findItemKey($productId, $attributes);

        if ($itemKey === null) {
            return false; // Item not found
        }

        $item = $this->items[$itemKey];

        // Check if we're changing attributes
        $changingAttributes = isset($updates['newAttributes']) && $updates['newAttributes'] != $attributes;

        if ($changingAttributes) {
            // Check if new attributes match another existing item
            $matchingItemKey = $this->findItemKey($productId, $updates['newAttributes']);

            if ($matchingItemKey !== null && $matchingItemKey !== $itemKey) {
                // Merge quantities with existing item
                $matchingItem = $this->items[$matchingItemKey];
                $newQuantity = isset($updates['quantity']) ? $updates['quantity'] : $item->getQuantity();
                $matchingItem->increaseQuantity($newQuantity);

                // Update price if provided
                if (isset($updates['price'])) {
                    $matchingItem->getProduct()->setPrice($updates['price']);
                }

                // Remove the old item
                unset($this->items[$itemKey]);
                $this->save();
                return true;
            }

            // Create new item with new attributes
            $newQuantity = isset($updates['quantity']) ? $updates['quantity'] : $item->getQuantity();
            $newPrice = isset($updates['price']) ? $updates['price'] : $item->getPrice();

            $newProduct = new Product($productId, $newPrice);
            $newItem = new CartItem($newProduct, $newQuantity, $updates['newAttributes']);

            $newKey = $this->generateItemKey($productId, $updates['newAttributes']);

            // Remove old and add new
            unset($this->items[$itemKey]);
            $this->items[$newKey] = $newItem;

            $this->save();
            return true;
        }

        // Update existing item (attributes not changed)
        if (isset($updates['quantity'])) {
            if ($updates['quantity'] <= 0) {
                return $this->remove($productId, $attributes);
            }

            $item->setQuantity($updates['quantity']);
        }

        if (isset($updates['price'])) {
            $item->getProduct()->setPrice($updates['price']);
        }

        $this->save();
        return true;
    }

    /**
     * Remove item by product ID and attributes
     * 
     * @param string $productId Product ID
     * @param array $attributes Attributes to identify item
     * @return bool Success status
     */
    public function remove($productId, array $attributes = [])
    {
        $itemKey = $this->findItemKey($productId, $attributes);

        if ($itemKey === null) {
            return false;
        }

        unset($this->items[$itemKey]);
        $this->save();
        return true;
    }

    /**
     * Get item by product ID and attributes
     * 
     * @param string $productId Product ID
     * @param array $attributes Attributes to identify item
     * @return CartItem|null Cart item or null
     */
    public function get($productId, array $attributes = [])
    {
        $itemKey = $this->findItemKey($productId, $attributes);
        return $itemKey !== null ? $this->items[$itemKey] : null;
    }

    /**
     * Get all cart items
     * 
     * @return CartItem[] Array of cart items
     */
    public function getAll()
    {
        return $this->items;
    }

    /**
     * Get all items for a specific product ID
     * 
     * @param string $productId Product ID
     * @return CartItem[] Array of matching cart items
     */
    public function getByProductId($productId)
    {
        $result = [];

        foreach ($this->items as $key => $item) {
            if ($item->getProductId() === $productId) {
                $result[$key] = $item;
            }
        }

        return $result;
    }

    /**
     * Get item count (number of line items)
     * 
     * @return int Item count
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Get total quantity of all items
     * 
     * @return int Total quantity
     */
    public function totalQuantity()
    {
        $total = 0;

        foreach ($this->items as $item) {
            $total += $item->getQuantity();
        }

        return $total;
    }

    /**
     * Get total quantity for specific product ID
     * 
     * @param string $productId Product ID
     * @return int Total quantity for product
     */
    public function totalQuantityByProductId($productId)
    {
        $total = 0;

        foreach ($this->items as $item) {
            if ($item->getProductId() === $productId) {
                $total += $item->getQuantity();
            }
        }

        return $total;
    }

    /**
     * Get cart total price
     * 
     * @return float|null Total price or null if any item has no price
     */
    public function getTotal()
    {
        $total = 0;

        foreach ($this->items as $item) {
            $itemTotal = $item->getTotal();

            if ($itemTotal != null) {
                $total += $itemTotal;
            }
        }

        return $total;
    }

    /**
     * Check if product exists in cart with specific attributes
     * 
     * @param string $productId Product ID
     * @param array $attributes Attributes
     * @return bool True if exists
     */
    public function has($productId, array $attributes = [])
    {
        return $this->findItemKey($productId, $attributes) !== null;
    }
    
    /**
     * Clear all items from cart
     */
    public function clear()
    {
        $this->items = [];
        $this->storage->clear();
    }

    /**
     * Check if cart is empty
     * 
     * @return bool True if empty
     */
    public function isEmpty()
    {
        return empty($this->items);
    }

    /**
     * Get cart data as array
     * 
     * @return array Cart data
     */
    public function toArray()
    {
        $data = [
            'items' => [],
            'summary' => [
                'count' => $this->count(),
                'totalQuantity' => $this->totalQuantity(),
                'total' => $this->getTotal(),
                'isEmpty' => $this->isEmpty()
            ]
        ];

        foreach ($this->items as $key => $item) {
            $data['items'][$key] = $item->toArray();
        }

        return $data;
    }

    /**
     * Find item key by product ID and attributes
     * 
     * @param string $productId Product ID
     * @param array $attributes Product attributes
     * @return string|null Item key or null if not found
     */
    private function findItemKey($productId, array $attributes = [])
    {
        foreach ($this->items as $key => $item) {
            if ($item->matches($productId, $attributes)) {
                return $key;
            }
        }

        return null;
    }

    /**
     * Load cart from storage
     */
    private function load()
    {
        $data = $this->storage->load();

        foreach ($data as $key => $itemData) {
            if (isset($itemData['productId'], $itemData['quantity'])) {
                $product = new Product(
                    $itemData['productId'],
                    isset($itemData['price']) ? $itemData['price'] : null
                );

                $item = new CartItem(
                    $product,
                    $itemData['quantity'],
                    isset($itemData['attributes']) ? $itemData['attributes'] : []
                );

                $this->items[$key] = $item;
            }
        }
    }

    /**
     * Save cart to storage
     */
    private function save()
    {
        $data = [];

        foreach ($this->items as $key => $item) {
            $data[$key] = [
                'productId' => $item->getProductId(),
                'quantity' => $item->getQuantity(),
                'price' => $item->getPrice(),
                'attributes' => $item->getAttributes()
            ];
        }

        $this->storage->save($data);
    }

    /**
     * Generate unique item key
     * 
     * @param string $productId Product ID
     * @param array $attributes Product attributes
     * @return string Unique key
     */
    private function generateItemKey($productId, array $attributes)
    {
        $key = $productId;

        if (!empty($attributes)) {
            ksort($attributes); // Sort attributes for consistency
            $key .= '_' . md5(serialize($attributes));
        }

        // Ensure key is unique
        $baseKey = $key;
        $counter = 1;

        while (isset($this->items[$key])) {
            $key = $baseKey . '_' . $counter;
            $counter++;
        }

        return $key;
    }
}

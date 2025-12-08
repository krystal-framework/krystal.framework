Cart
===
E-commerce is widespread on today’s internet, and shopping carts are a standard way for users to collect and manage products. This package provides a simple API for working with cart items stored in a session.

Import it first:

    <?php
    
    use Krystal\Cart\SessionAdapter;
    use Krystal\Cart\ShoppingCart;
    
    $sessionAdapter = new SessionAdapter();
    $cart = new ShoppingCart($sessionAdapter);

## Add items

Only the item ID is required. Attributes, price, and quantity are optional.

    // Add product with ID = 9, with default quantity = 1 and no attributes or price
    $cart->add(9);
    
    // Add product with ID = 10, with quantity 2 and attributes
    $cart->add(10, 2, ['color' => 'red', 'size' => 'XXL']);
    
    // Add product with ID = 11, quantity 3, no attributes, and a fixed price of 99
    $cart->add(11, 3, [], 99);

## Update items

If multiple items share the same ID but have different attributes, you must specify the attributes of the item you want to update.

    // Update product (ID = 10) with given attributes
    $cart->update(
        10,
        ['color' => 'red', 'size' => 'XXL'],
        [
            'quantity' => 5,      // Optional
            'price' => 112,       // Optional
            'newAttributes' => [  // Optional
                'color' => 'blue'
            ]
        ]
    );

## Remove items

If attributes are not provided, _all_ items with the same ID will be removed.

    // Remove all items with ID = 11
    $cart->remove(11);
    
    // Remove only the item with matching ID and attributes
    $cart->remove(10, ['color' => 'red', 'size' => 'XXL']);

## Getting a summary

Returns a multi-dimensional array containing all items and a summary block.

`$cart->toArray();` 

The returned structure contains:

-   `items` — All cart items
-   `summary` — Aggregated information (totals, counts, etc.)

## Get a single Item

Retrieves a multi-dimensional array describing one specific item stored in the cart.

    $item = $cart->get(10, ['color' => 'red', 'size' => 'XXL']);


## Check if cart is empty

    $cart->isEmpty(); // Returns true or false


## Clear cart

Clears all items from the cart.

    $cart->clear();

Event manager
=============

The `EventManager` class provides a lightweight event system that allows you to **register**, **detach**, and **trigger** named events dynamically at runtime.  

It is designed to simplify the management of callbacks (closures) that respond to specific application events.

This implementation supports:

-   Single and bulk event attachment
-   Event triggering by name (or through magic method calls)
-   Checking for event existence
-   Detaching single or all events
-   Counting registered events


##  Basic usage

Attaching and Triggering Events

    <?php
    
    use Krystal\Event\EventManager;
    
    $events = new EventManager();
    
    // Attach an event called 'onSave'
    $events->attach('onSave', function() {
        echo 'Data has been saved!';
    });
    
    // Trigger the event manually
    $events->trigger('onSave'); // Output: Data has been saved!

## Using magic method calls

You can trigger events simply by calling them as methods:

    $events->onSave(); // Equivalent to $events->trigger('onSave');

## Attaching multiple events

You can attach multiple events at once using an associative array:

    $events->attachMany([
        'onStart' => function() { echo 'Application started'; },
        'onStop'  => function() { echo 'Application stopped'; }
    ]);
    
    $events->trigger('onStart'); // Output: Application started
    $events->trigger('onStop');  // Output: Application stopped

## Detaching events

    // Remove one event
    $events->detach('onSave');
    
    // Remove all registered events
    $events->detachAll();

If you try to detach an event that doesn’t exist, a `RuntimeException` will be thrown.

## Checking event existence

    $events->has('onStart');       // true or false
    $events->hasMany(['onStart', 'onStop']); // true if all exist

## Counting registered events

    echo $events->countAll(); // e.g., 2

## Errors

-   **`InvalidArgumentException`** — thrown if event name is not a string or listener is not callable.
    
-   **`RuntimeException`** — thrown when trying to trigger or detach a non-existing event.
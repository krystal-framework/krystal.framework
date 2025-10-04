Virtual Entity
======

The `VirtualEntity` class provides a **dynamic entity container** that allows you to define, read, and modify object-like properties **without explicitly declaring them**.  

It works like a lightweight **data value object (DVO)** or **entity proxy**, supporting:

-   **Virtual getters and setters** (`getName()`, `setName($value)`).
-   **Array-like access** (`$entity['name']`).
-   **Optional write-once behavior** to prevent overwriting existing values.
-   **Optional strict mode**, which enforces method name conventions (must start with `get` or `set`).
-   **Automatic snake_case conversion** for property names.
-   **Optional sanitization** of values through `Krystal\Security\Filter`.
    
This class is particularly useful when dealing with flexible or dynamic data structures, where property names may not be known at compile time.


## Basic usage

    <?php
    
    use Krystal\Stdlib\VirtualEntity;
    
    $entity = new VirtualEntity();
    
    // Set virtual properties
    $entity->setName('Alice');
    $entity->setEmail('alice@example.com');
    
    // Get virtual properties
    echo $entity->getName();  // Output: Alice
    echo $entity->getEmail(); // Output: alice@example.com


## Using array syntax

    $entity = new VirtualEntity();
    
    $entity['name'] = 'Bob';
    echo $entity['name']; // Output: Bob
    
    unset($entity['name']);
    var_dump(isset($entity['name'])); // false


## Enabling write-once protection

This example demonstrates the **write-once mode** controlled by the `$once` constructor argument.

When `$once = true`, each property in the `VirtualEntity` can be **assigned only once**.  If you try to set the same property again, the class throws a `RuntimeException`.

This is useful when you want to make entity data **immutable after initialization**, ensuring that once a value is written, it cannot be accidentally overwritten later in the code — for example, in configuration objects, request data, or entities that should remain stable after creation.

    $entity = new VirtualEntity($once = true);
    
    $entity->setUsername('first');
    
    // The next line throws RuntimeException because write-once mode is active
    $entity->setUsername('second');

## Using strict mode

When enabled (`$strict = true`), it ensures that all dynamic method calls must begin with either `get` or `set`.

This helps **catch typos and logic errors early** — for example, calling `$entity->fetchName()` would throw an exception instead of silently doing nothing.

**Use strict mode** when you want your entities to behave predictably and fail fast if someone tries to call an invalid or misspelled virtual method.

    $entity = new VirtualEntity($once = false, $strict = true);
    
    // This will throw a RuntimeException because "fetchName" is not allowed
    $entity->fetchName();


## Sanitization support

Sanitization helps ensure that data stored in entities is **safe and consistent**, especially when it originates from user input.

By combining `VirtualEntity` with `Sanitizeable` filters, you can protect your application from **XSS attacks**, **data corruption**, and **unexpected type issues** — all while keeping your code clean and declarative.

`VirtualEntity` can sanitize property values automatically when calling a setter.  


    <?php
    
    use Krystal\Stdlib\VirtualEntity;
    use Krystal\Security\Sanitizeable;
    
    $entity = new VirtualEntity();
    
    // Set a value with HTML tags, sanitized using a defined filter
    $entity->setComment('<b>Hello</b>', Sanitizeable::FILTER_HTML);
    
    echo $entity->getComment(); 
    // Output: Hello  (HTML removed)

You can pass a **second argument** to a `set*()` method that specifies which filter to apply, using one of the constants from `Krystal\Security\Sanitizeable`.

| Constant | Description |
|-----------|-------------|
| `Sanitizeable::FILTER_NONE` | No filtering (value remains unchanged). |
| `Sanitizeable::FILTER_FLOAT` | Converts value to a floating-point number. |
| `Sanitizeable::FILTER_INT` | Converts value to an integer. |
| `Sanitizeable::FILTER_BOOL` | Converts value to a boolean. |
| `Sanitizeable::FILTER_HTML` | Removes HTML markup entirely. |
| `Sanitizeable::FILTER_TAGS` | Strips all HTML and PHP tags. |
| `Sanitizeable::FILTER_SAFE_TAGS` | Allows only safe HTML tags (like `<b>`, `<i>`, etc.). |
| `Sanitizeable::FILTER_HTML_CHARS` | Escapes HTML characters (e.g., `<` becomes `&lt;`). |


## Getting all properties

    $entity = new VirtualEntity();
    
    $entity->setName('Alice');
    $entity->setRole('Admin');
    
    print_r($entity->getProperties());

This will output

    Array
    (
        [name] => Alice
        [role] => Admin
    )


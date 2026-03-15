Array Collections
=================

The **ArrayCollection** and **ArrayGroupCollection** classes provide convenient, reusable abstractions for working with constant-like key-value mappings (e.g. status codes, roles, priorities).  

They are designed to be extended in your modules or application code.

- `ArrayCollection` – simple flat key-value storage
- `ArrayGroupCollection` – grouped key-value storage (multiple categories)

These classes help centralize constants, improve type safety, and make lookups cleaner than raw arrays.

They are typically used as base classes you extend with your own constant definitions.

**Example use case**

    <?php

    use Krystal\Stdlib\ArrayCollection;

    class StatusCollection extends ArrayCollection
    {
        protected $collection = [
            1 => 'Active',
            2 => 'Disabled',
            3 => 'Deleted',
        ];
    }

## Flat Collection

Simple key-value lookup for flat constants (e.g. status codes, priorities, types).

Extend this class and define your `$collection` property.

Typical usage

    <?php

    use Krystal\Stdlib\ArrayCollection;

    class UserStatusCollection extends ArrayCollection
    {
        protected $collection = [
            1 => 'Active',
            2 => 'Suspended',
            3 => 'Banned',
        ];
    }

### Check if key exists

    hasKey($key): bool

Determine whether a specific key is defined in the collection.

**Example**

    <?php

    $statuses = new UserStatusCollection();

    if ($statuses->hasKey(1)) {
        // Active status exists
    }

### Check if multiple keys exist

    hasKeys(array $keys): bool

Verify that all specified keys are present in the collection.

**Example**

    if ($statuses->hasKeys([1, 3])) {
        // Both Active and Banned are defined
    }

### Get value by key with fallback

    findByKey($key, $default = ''): mixed

Retrieve a value by key, returning a default if the key is missing.

    $statusName = $statuses->findByKey(1, 'Unknown'); // 'Active'
    $missing = $statuses->findByKey(99, 'Unknown'); // 'Unknown'

### Get all values

    getAll(): array

Return the full collection array.

## Grouped Collection

Organize constants into named groups (e.g. user statuses, order statuses, payment methods).
Useful when you have multiple related sets of constants.

Extend this class and define grouped $collection arrays.

**Typical usage**

    <?php

    use Krystal\Stdlib\ArrayGroupCollection;

    class OrderStatusCollection extends ArrayGroupCollection
    {
        protected $collection = [
            'pending' => [
                1 => 'New',
                2 => 'Awaiting Payment',
            ],
            'processing' => [
                10 => 'Processing',
                11 => 'Shipped',
            ],
            'completed' => [
                20 => 'Delivered',
                21 => 'Completed',
            ],
        ];
    }

### Check if key exists (in any group)

    hasKey($target): bool

Determine whether a key exists in any of the groups.

**Example**

    $orderStatuses = new OrderStatusCollection();

    if ($orderStatuses->hasKey(10)) {
        // Processing status exists
    }

### Get value by key (from any group) with fallback

    findByKey($target, $default = ''): mixed

Retrieve a value by key across all groups, returning a default if missing.

**Example**

$status = $orderStatuses->findByKey(10, 'Unknown'); // 'Processing'
$missing = $orderStatuses->findByKey(999, 'Unknown'); // 'Unknown'

### Get all values (flattened)

    getAll(): array

Return a flattened array of all key-value pairs across groups.

**Example**

    print_r($orderStatuses->getAll());
    // Array ( [1] => New [2] => Awaiting Payment [10] => Processing ... )

## Quick Tips & Best Practices

- Extend these classes for each domain-specific constant set (statuses, roles, priorities, types)
- Use uppercase class names ending in Collection (e.g. UserRoleCollection)
- Keep keys as integers or strings — avoid complex types
- Use findByKey() with a default value to avoid undefined index notices
- Prefer hasKey() checks before findByKey() when the key is optional
- Group related constants in ArrayGroupCollection to improve organization
- Define constants in one place — avoid scattering them across controllers/models
- Use these collections in forms, dropdowns, badges, and validation rules
- Document your collections (add PHPDoc or constants list) for team clarity
- Consider adding helper methods in child classes (e.g. getActive(), isValidStatus())
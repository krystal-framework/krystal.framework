
Array Utilites
======

A collection of methods to work with arrays.

## Fingerprint

Creates a lightweight hash fingerprint of an array for quick identification or change detection.

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $data = [
        'id' => 123,
        'name' => 'Alice',
        'roles' => ['admin', 'editor']
    ];
    
    // Generate a fingerprint for the array
    $fingerprint = ArrayUtils::fingerprint($data);
    
    echo "Fingerprint: " . $fingerprint;

This will output

    Fingerprint: 9a3f5b7c


## Categorize an array

Categorize an array of rows by a given key.

Usage example:

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $products = [
        ['id' => 1, 'category' => 'Books', 'title' => 'Clean Code'],
        ['id' => 2, 'category' => 'Books', 'title' => 'Refactoring'],
        ['id' => 3, 'category' => 'Games', 'title' => 'Chess Set'],
        ['id' => 4, 'category' => 'Games', 'title' => 'Monopoly'],
        ['id' => 5, 'category' => 'Music', 'title' => 'Guitar']
    ];
    
    $categorized = ArrayUtils::categorize($products, 'category');
    
    print_r($categorized);

This will output the following:

    Array
    (
        [0] => Array
            (
                [name] => Books
                [count] => 2
                [items] => Array
                    (
                        [0] => Array
                            (
                                [id] => 1
                                [category] => Books
                                [title] => Clean Code
                            )
    
                        [1] => Array
                            (
                                [id] => 2
                                [category] => Books
                                [title] => Refactoring
                            )
                    )
            )
    
        [1] => Array
            (
                [name] => Games
                [count] => 2
                [items] => Array
                    (
                        [0] => Array
                            (
                                [id] => 3
                                [category] => Games
                                [title] => Chess Set
                            )
    
                        [1] => Array
                            (
                                [id] => 4
                                [category] => Games
                                [title] => Monopoly
                            )
                    )
            )
    
        [2] => Array
            (
                [name] => Music
                [count] => 1
                [items] => Array
                    (
                        [0] => Array
                            (
                                [id] => 5
                                [category] => Music
                                [title] => Guitar
                            )
                    )
            )
    )

## Parse arguments

Parses a list of arguments into a structured array with a master element and normalized parameters.

### Example 1

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    // Example 1: Passing master and a flat list of arguments
    $result = ArrayUtils::parseArgs(['command', 'arg1', 'arg2']);
    
    print_r($result);

This will output the following:

    Array
    (
        [master] => command
        [arguments] => Array
            (
                [0] => arg1
                [1] => arg2
            )
    )


### Example 2

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    // Passing master and an array of arguments
    $result = ArrayUtils::parseArgs(['command', ['arg1', 'arg2']]);
    
    print_r($result);

This will output the following:

    Array
    (
        [master] => command
        [arguments] => Array
            (
                [0] => arg1
                [1] => arg2
            )
    )

## Iterable check

Checks if a variable is an array or implements common iterable interfaces.

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    // Example 1: Array
    $data = [1, 2, 3];
    var_dump(ArrayUtils::isIterable($data)); // true
    
    // Example 2: Traversable object
    $iterator = new ArrayIterator([1, 2, 3]);
    var_dump(ArrayUtils::isIterable($iterator)); // true
    
    // Example 3: Non-iterable scalar
    $value = 42;
    var_dump(ArrayUtils::isIterable($value)); // false
    
    // Example 4: Object implementing Countable
    class Dummy implements Countable {
        public function count(): int {
            return 0;
        }
    }
    var_dump(ArrayUtils::isIterable(new Dummy())); // true

## Unset by value

Removes the first matching value from an array, if found.

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $fruits = ['apple', 'banana', 'cherry', 'banana'];
    
    // Remove first occurrence of "banana"
    $result = ArrayUtils::unsetByValue($fruits, 'banana');
    
    print_r($result);

This will output:

    Array
    (
        [0] => apple
        [2] => cherry
        [3] => banana
    )

## Dropdown

Builds grouped key–value arrays (e.g., for dropdowns) from a partitioned dataset.

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $data = [
        ['id' => 1, 'category' => 'Books', 'title' => 'Clean Code'],
        ['id' => 2, 'category' => 'Books', 'title' => 'Refactoring'],
        ['id' => 3, 'category' => 'Games', 'title' => 'Chess Set'],
        ['id' => 4, 'category' => 'Games', 'title' => 'Monopoly'],
        ['id' => 5, 'category' => 'Music', 'title' => 'Guitar']
    ];
    
    // Dropdown-style structure (id => title grouped by category)
    $dropdown = ArrayUtils::arrayDropdown($data, 'category', 'id', 'title');
    
    print_r($dropdown);

This will output:

    Array
    (
        [Books] => Array
            (
                [1] => Clean Code
                [2] => Refactoring
            )
    
        [Games] => Array
            (
                [3] => Chess Set
                [4] => Monopoly
            )
    
        [Music] => Array
            (
                [5] => Guitar
            )
    )
    
## Partition

Groups an array of rows or objects by a specified key.

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $data = [
        ['id' => 1, 'category' => 'Books', 'title' => 'Clean Code'],
        ['id' => 2, 'category' => 'Books', 'title' => 'Refactoring'],
        ['id' => 3, 'category' => 'Games', 'title' => 'Chess Set'],
        ['id' => 4, 'category' => 'Games', 'title' => 'Monopoly'],
        ['id' => 5, 'category' => 'Music', 'title' => 'Guitar']
    ];
    
    // Example 1: Partition data by category
    $partitioned = ArrayUtils::arrayPartition($data, 'category');
    
    print_r($partitioned);

This will output

    Array
    (
        [Books] => Array
            (
                [0] => Array ( [id] => 1 [category] => Books [title] => Clean Code )
                [1] => Array ( [id] => 2 [category] => Books [title] => Refactoring )
            )
    
        [Games] => Array
            (
                [0] => Array ( [id] => 3 [category] => Games [title] => Chess Set )
                [1] => Array ( [id] => 4 [category] => Games [title] => Monopoly )
            )
    
        [Music] => Array
            (
                [0] => Array ( [id] => 5 [category] => Music [title] => Guitar )
            )
    )


## Recovery

Ensures required keys exist in an array, filling missing ones with a default value.

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $user = [
        'id' => 1,
        'name' => 'Alice'
    ];
    
    // Ensure 'email' and 'role' keys exist, defaulting to null
    $recovered = ArrayUtils::arrayRecovery($user, ['email', 'role'], null);
    
    print_r($recovered);

This will output

    Array
    (
        [id] => 1
        [name] => Alice
        [email] => 
        [role] => 
    )

## Recursive callback

Recursively applies a callback to all values in a multidimensional array.

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    // Example array with nested levels
    $data = [
        'name' => ' Alice ',
        'roles' => [
            ' admin ',
            ' editor '
        ],
        'meta' => [
            'email' => '  alice@example.com  ',
            'status' => ' active '
        ]
    ];
    
    // Trim whitespace from all string values
    $cleaned = ArrayUtils::filterValuesRecursively($data, function ($value) {
        return is_string($value) ? trim($value) : $value;
    });
    
    print_r($cleaned);

This will output

    Array
    (
        [name] => Alice
        [roles] => Array
            (
                [0] => admin
                [1] => editor
            )
    
        [meta] => Array
            (
                [email] => alice@example.com
                [status] => active
            )
    )

## Callback

Applies a callback to each element of an array and returns the transformed array.

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $numbers = [1, 2, 3, 4, 5];
    
    // Square each number
    $squared = ArrayUtils::filterArray($numbers, function ($n) {
        return $n * $n;
    });
    
    print_r($squared);

This will output

    Array
    (
        [0] => 1
        [1] => 4
        [2] => 9
        [3] => 16
        [4] => 25
    )

## Set of keys

Checks if an array contains exactly the specified set of keys.

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $user = [
        'id' => 1,
        'name' => 'Alice',
        'email' => 'alice@example.com'
    ];
    
    // Must contain exactly 'id', 'name', and 'email'
    var_dump(ArrayUtils::keysExist($user, ['id', 'name', 'email'])); 
    // true
    
    // Missing one key
    var_dump(ArrayUtils::keysExist($user, ['id', 'name'])); 
    // false
    
    // Extra key
    var_dump(ArrayUtils::keysExist($user, ['id', 'name', 'email', 'role'])); 
    // false

## Combine

### Example 1: Equal length arrays

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $keys = ['a', 'b', 'c'];
    $values = [1, 2, 3];
    
    print_r(ArrayUtils::arrayCombine($values, $keys));
    
    // Output: ['a' => 1, 'b' => 2, 'c' => 3]


### Example 2: Unequal length arrays with replacement

Safely combines two arrays into key-value pairs, handling unequal lengths and allowing order control

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $keys = ['x', 'y'];
    $values = [10, 20, 30];
    
    print_r(ArrayUtils::arrayCombine($values, $keys, 0));
    
    // Output: ['x' => 10, 'y' => 20, 30 => 0]


### Example 3: Switch order (keys ← first array, values ← second array)


    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $keys = ['k1', 'k2', 'k3'];
    $values = ['v1', 'v2', 'v3'];
    
    print_r(ArrayUtils::arrayCombine($keys, $values, null, false));
    // Output: ['k1' => 'v1', 'k2' => 'v2', 'k3' => 'v3']

## Prepend

Prepends a key-value pair to an associative array while preserving key order.

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $data = [
        'b' => 2,
        'c' => 3,
    ];
    
    // Prepend 'a' => 1
    ArrayUtils::assocPrepend($data, 'a', 1);
    
    print_r($data);

This will output:

    Array
    (
        [a] => 1
        [b] => 2
        [c] => 3
    )

## List

Builds a key-value list from a multidimensional array using specified indexes.

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $data = [
        ['id' => 1, 'name' => 'Alice', 'age' => 30],
        ['id' => 2, 'name' => 'Bob', 'age' => 25],
        ['id' => 3, 'name' => 'Charlie', 'age' => 40],
    ];
    
    // Build list: id => name
    $list = ArrayUtils::arrayList($data, 'id', 'name');
    
    print_r($list);

This will output

    Array
    (
        [1] => Alice
        [2] => Bob
        [3] => Charlie
    )

## Search

Recursively searches for a value in a multidimensional array and returns the path of keys.

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $data = [
        'users' => [
            ['id' => 1, 'name' => 'Alice'],
            ['id' => 2, 'name' => 'Bob'],
        ],
        'settings' => [
            'theme' => 'dark',
            'language' => 'en',
        ],
    ];
    
    // Search for a string
    $result = ArrayUtils::search($data, 'Bob');
    print_r($result);

This will output

    Array
    (
        [0] => users
        [1] => 1
        [2] => name
    )

## Unique

### Example 1: Duplicate scalar values

    <?php

    use Krystal\Stdlib\ArrayUtils;
    
    $data = [1, 2, 2, 3, 3, 3];
    
    print_r(ArrayUtils::arrayUnique($data));

This will output

    Array
    (
        [0] => 1
        [1] => 2
        [3] => 3
    )

### Example 2: Duplicate arrays

    <?php

    use Krystal\Stdlib\ArrayUtils;
    
    $data = [
        ['id' => 1, 'name' => 'Alice'],
        ['id' => 2, 'name' => 'Bob'],
        ['id' => 1, 'name' => 'Alice'], // duplicate
    ];
    
    print_r(ArrayUtils::arrayUnique($data));

This will output

    Array
    (
        [0] => Array
            (
                [id] => 1
                [name] => Alice
            )
        [1] => Array
            (
                [id] => 2
                [name] => Bob
            )
    )

### Example 3: Mixed values

Removes duplicates from multidimensional arrays by comparing serialized values.

    <?php

    use Krystal\Stdlib\ArrayUtils;

    $data = [
        10,
        "10",
        10, // duplicate
        ["a" => 1],
        ["a" => 1], // duplicate array
    ];
    
    print_r(ArrayUtils::arrayUnique($data));

This will output

    Array
    (
        [0] => 10
        [1] => 10
        [3] => Array
            (
                [a] => 1
            )
    )

## Are values arrays?

Checks if all elements of an array are arrays (validates two-dimensional structure)

### Example 1: All values are arrays

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $data = [
        ['id' => 1, 'name' => 'Alice'],
        ['id' => 2, 'name' => 'Bob'],
    ];
    
    var_dump(ArrayUtils::hasAllArrayValues($data)); 
    
    // bool(true)

### Example 2: Mixed values (one scalar inside)

    <?php
    
    use Krystal\Stdlib\ArrayUtils;

    $data = [
        ['id' => 1],
        'not-an-array',
    ];
    
    var_dump(ArrayUtils::hasAllArrayValues($data)); 
    
    // bool(false)

## Has nested arrays?

Checks if an array contains at least one nested array.

### Example 1: Contains nested arrays

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $data = [
        'id' => 1,
        'profile' => ['name' => 'Alice', 'age' => 30],
    ];
    
    var_dump(ArrayUtils::hasAtLeastOneArrayValue($data));
    
    // bool(true)


### Example 2: No nested arrays

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $data = [
        'id' => 2,
        'name' => 'Bob',
    ];
    
    var_dump(ArrayUtils::hasAtLeastOneArrayValue($data));
    
    // bool(false)


## Sequential

Checks if an array has consecutive integer keys starting at 0 (sequential list).


### Example 1: Sequential array

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $data = ['a', 'b', 'c']; // implicit keys: 0, 1, 2
    var_dump(ArrayUtils::isSequential($data));
    
    // bool(true)

### Example 2: Not sequential (starts at 1)

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $data = [1 => 'a', 2 => 'b'];
    var_dump(ArrayUtils::isSequential($data));
    
    // bool(false)

### Example 3: Not sequential (associative)

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $data = ['x' => 10, 'y' => 20];
    var_dump(ArrayUtils::isSequential($data));
    
    // bool(false)

### Example 4: Empty array

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $data = [];
    var_dump(ArrayUtils::isSequential($data));
    
    // bool(false) — empty array is not considered sequential

## Assoc

Checks if an array is associative (non-sequential keys).

### Example 1: Associative array

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $data = ['a' => 1, 'b' => 2];
    var_dump(ArrayUtils::isAssoc($data));
    
    // bool(true)

### Example 2: Sequential array

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $data = [10, 20, 30]; // implicit keys: 0,1,2
    var_dump(ArrayUtils::isAssoc($data));
    
    // bool(false)

### Example 3: Mixed keys

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $data = [0 => 'first', 2 => 'third'];
    var_dump(ArrayUtils::isAssoc($data));
    
    // bool(true) — gap in numeric keys makes it associative

### Example 4: Empty array

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $data = [];
    var_dump(ArrayUtils::isAssoc($data));
    
    // bool(false) — empty arrays are not considered sequential

## Without

Removes specified keys from an array, with optional support for nested arrays.


### Example 1: Single associative array

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $data = [
        'id' => 1,
        'name' => 'Alice',
        'password' => 'secret'
    ];
    
    $result = ArrayUtils::arrayWithout($data, ['password']);
    print_r($result);

This will output

    Array
    (
        [id] => 1
        [name] => Alice
    )

### Example 2: Multidimensional array (applies to each row)

    <?php
    
    use Krystal\Stdlib\ArrayUtils;

    $data = [
        ['id' => 1, 'name' => 'Alice', 'password' => '123'],
        ['id' => 2, 'name' => 'Bob', 'password' => '456'],
    ];

    $result = ArrayUtils::arrayWithout($data, ['password']);
    print_r($result);

This will output

    Array
    (
        [0] => Array
            (
                [id] => 1
                [name] => Alice
            )
        [1] => Array
            (
                [id] => 2
                [name] => Bob
            )
    )

### Example 3: Keys not present (no effect)

    <?php
    
    use Krystal\Stdlib\ArrayUtils;

    $data = ['id' => 3, 'name' => 'Charlie'];
    $result = ArrayUtils::arrayWithout($data, ['nonexistent']);
    
    print_r($result);

This will output

    Array
    (
        [id] => 3
        [name] => Charlie
    )



## Only

Filters an array to include only the specified keys (for associative arrays) or values (for sequential arrays).


### Example 1: Associative array (filter by keys)

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $data = [
        'id' => 1,
        'name' => 'Alice',
        'role' => 'admin',
        'password' => 'secret'
    ];
    
    $result = ArrayUtils::arrayOnlyWith($data, ['id', 'name']);
    print_r($result);

This will output

    Array
    (
        [id] => 1
        [name] => Alice
    )

### Example 2: Sequential array (filter by values)

    <?php
    
    use Krystal\Stdlib\ArrayUtils;

    $data = ['apple', 'banana', 'cherry', 'date'];
    
    $result = ArrayUtils::arrayOnlyWith($data, ['banana', 'date']);
    print_r($result);

This will output

    Array
    (
        [0] => banana
        [1] => date
    )

### Example 3: Keys/values not found

    <?php
    
    use Krystal\Stdlib\ArrayUtils;

    $data = ['id' => 10, 'name' => 'Bob'];
    $result = ArrayUtils::arrayOnlyWith($data, ['nonexistent']);
    print_r($result);

This will output

    Array
    (
    )

## Merge without

Merges two arrays and excludes specific keys from the final result.

### Example 1: Remove sensitive fields after merging

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $userDefaults = [
        'role' => 'guest',
        'status' => 'active'
    ];
    
    $userData = [
        'id' => 1,
        'name' => 'Alice',
        'password' => 'secret'
    ];
    
    $result = ArrayUtils::mergeWithout($userDefaults, $userData, ['password']);
    print_r($result);

This will output

    Array
    (
        [role] => guest
        [status] => active
        [id] => 1
        [name] => Alice
    )

### Example 2: Works with overlapping keys

    <?php
    
    use Krystal\Stdlib\ArrayUtils;

    $a = ['id' => 1, 'name' => 'Bob'];
    $b = ['name' => 'Alice', 'email' => 'alice@example.com'];
    
    $result = ArrayUtils::mergeWithout($a, $b, ['email']);
    print_r($result);

This will output

    Array
    (
        [id] => 1
        [name] => Alice
    )

## Column sum

Sums values of one or more columns across a dataset of associative arrays.

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $orders = [
        ['id' => 1, 'amount' => 100, 'tax' => 20],
        ['id' => 2, 'amount' => 50, 'tax' => 10],
        ['id' => 3, 'amount' => 80, 'tax' => 16],
    ];
    
    // Sum up "amount" and "tax" columns
    $totals = ArrayUtils::columnSum($orders, ['amount', 'tax']);
    print_r($totals);

This will output

    Array
    (
        [amount] => 230
        [tax] => 46
    )

## Calculate the sum

Sums all columns across a dataset by auto-detecting available column names.

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $invoices = [
        ['id' => 1, 'subtotal' => 200, 'tax' => 40, 'discount' => 10],
        ['id' => 2, 'subtotal' => 100, 'tax' => 20, 'discount' => 5],
        ['id' => 3, 'subtotal' => 50,  'tax' => 10, 'discount' => 0],
    ];
    
    // Automatically sums all detected columns
    $totals = ArrayUtils::sumColumns($invoices);
    print_r($totals);

This will output

    Array
    (
        [id] => 6
        [subtotal] => 350
        [tax] => 70
        [discount] => 15
    )

### Sum with averages

Sums all columns in a dataset and optionally replaces specified columns with their averages.


### Sum all columns, average only "score"

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $students = [
        ['id' => 1, 'score' => 90, 'age' => 20],
        ['id' => 2, 'score' => 80, 'age' => 21],
        ['id' => 3, 'score' => 70, 'age' => 19],
    ];
    
    $result = ArrayUtils::sumColumnsWithAverages($students, ['score'], 1);
    print_r($result);

This will output

    Array
    (
        [id] => 6
        [score] => 80.0   // average of (90+80+70)/3
        [age] => 60
    )

### Without rounding

    <?php
    
    use Krystal\Stdlib\ArrayUtils;

    $result = ArrayUtils::sumColumnsWithAverages($students, ['score'], false);
    
    print_r($result);

This will output

    Array
    (
        [id] => 6
        [score] => 80     // raw float average
        [age] => 60
    )


## Get columns

Returns the keys of the first row in a dataset, treating them as column names.

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $users = [
        ['id' => 1, 'name' => 'Alice', 'email' => 'alice@example.com'],
        ['id' => 2, 'name' => 'Bob',   'email' => 'bob@example.com'],
    ];
    
    // Extract column names
    $columns = ArrayUtils::arrayColumns($users);
    print_r($columns);

This will output

    Array
    (
        [0] => id
        [1] => name
        [2] => email
    )



## Valuefy

Creates a key-value map from an array using either its values or its keys.



### Example 1: Use array values

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $colors = ['red', 'green', 'blue'];
    $result = ArrayUtils::valuefy($colors, true);
    print_r($result);

This will output

    Array
    (
        [red] => red
        [green] => green
        [blue] => blue
    )

### Example 2: Use array keys

    <?php
    
    use Krystal\Stdlib\ArrayUtils;

    $users = [
        'u1' => 'Alice',
        'u2' => 'Bob'
    ];
    
    $result = ArrayUtils::valuefy($users, false);
    print_r($result);

This will output

    Array
    (
        [u1] => u1
        [u2] => u2
    )

## Round values

Recursively rounds all numeric values in an array to a given precision, converting empty values to zero.

    <?php
    
    use Krystal\Stdlib\ArrayUtils;
    
    $data = [
        'price' => 10.567,
        'discount' => null,
        'items' => [
            ['qty' => 1.2345, 'subtotal' => 5.6789],
            ['qty' => 2.3456, 'subtotal' => 8.91011]
        ]
    ];
    
    $result = ArrayUtils::roundValues($data, 2);
    
    print_r($result);

This will output

    Array
    (
        [price] => 10.57
        [discount] => 0
        [items] => Array
            (
                [0] => Array
                    (
                        [qty] => 1.23
                        [subtotal] => 5.68
                    )
    
                [1] => Array
                    (
                        [qty] => 2.35
                        [subtotal] => 8.91
                    )
            )
    )



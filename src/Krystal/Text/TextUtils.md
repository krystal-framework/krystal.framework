Text Utils
=======

Utility class for working with strings, providing a rich set of methods for common text operations.

## Contains

Checks if a given substring exists within a string, compatible with both modern and legacy PHP versions.

    <?php
    
    use Krystal\Text\TextUtils;
    
    $text = "Hello, world!";
    
    $result = TextUtils::contains($text, "world"); // true
    $result = TextUtils::contains($text, "php");   // false

## Break string

Splits a string into lines and trims whitespace from each line.

    <?php
    
    use Krystal\Text\TextUtils;
    
    $text = <<<EOT
      Line one  
    Line two 
      Line three
    EOT;
    
    $lines = TextUtils::breakString($text);
    
    print_r($lines);

This will output

    Array
    (
        [0] => Line one
        [1] => Line two
        [2] => Line three
    )

## String modified

Determines if a string changes after being processed by a callback function.

    <?php
    
    use Krystal\Text\TextUtils;
    
    $text = "Hello World";
    
    $modified = TextUtils::strModified($text, function($str) {
        return strtoupper($str);
    });
    
    var_dump($modified); // True

## Serial

Generates a customizable serial number, optionally unique and uppercase, with defined length and portion size.

    <?php
    
    use Krystal\Text\TextUtils;
    
    $serial = TextUtils::serial("user123");
    echo $serial; // AB12C-DE34F-GH56I-JK78L-MN90O

## Normalize column

Converts a column name from snake_case to a human-readable format with capitalized words.

    <?php
    
    use Krystal\Text\TextUtils;
    
    $column = "first_name";
    $normalized = TextUtils::normalizeColumn($column);
    
    echo $normalized; // First Name

## Unique hash

Generates a unique MD5-hashed string based on time and randomness

    <?php
    
    use Krystal\Text\TextUtils;
    
    $unique = TextUtils::uniqueString();
    echo $unique; // 9b74c9897bac770ffc029102a200c5de

## Random string

Creates a random string of specified length using letters, numbers, or both

    <?php
    
    use Krystal\Text\TextUtils;
    
    // Letters only
    $alpha = TextUtils::randomString(10, 'alpha');
    echo $alpha; // aZbTcDfGhJ
    
    // Letters and numbers
    $alnum = TextUtils::randomString(10, 'alnum');
    echo $alnum; // 9aB4k2L7pQ
    
    // Numbers only
    $numeric = TextUtils::randomString(10, 'numeric');
    print_r($numeric); // 3948271056

## Find occurrences

Finds all occurrences of a substring in a string and returns their start and end positions.

    <?php
    
    use Krystal\Text\TextUtils;
    
    $text = "This is a test. Testing is fun. Test again.";
    $positions = TextUtils::getNeedlePositions($text, "Test");
    print_r($positions);

This will output

    Array
    (
        [15] => 19
        [33] => 37
    )

## Trim

Trims a string to a specified length and appends a suffix if truncated.

    <?php
    
    use Krystal\Text\TextUtils;
    
    $text = "This is a very long sentence that needs trimming.";
    
    $trimmed = TextUtils::trim($text, 20);
    echo $trimmed; // This is a very long  .... 

## Slug

Converts a string into a URL-friendly slug, optionally romanizing non-Latin characters.

    <?php
    
    use Krystal\Text\TextUtils;
    
    $text = "Hello World!";
    $slug = TextUtils::sluggify($text);
    
    echo $slug; // hello-world

## Romanize

Converts non-Latin characters in a string to their Latin equivalents.

    <?php
    
    use Krystal\Text\TextUtils;
    
    $text = "Ça va bien, frère?";
    $romanized = TextUtils::romanize($text);
    
    echo $romanized; // Ca va bien, frere?

## Snake case

Converts a string to snake_case, handling both spaces and camelCase.

    <?php
    
    use Krystal\Text\TextUtils;
    
    echo TextUtils::snakeCase('Hello World'); // hello_world
    echo TextUtils::snakeCase('camelCase'); // camel_case

## Studly case

Converts a string to StudlyCase, capitalizing each word and removing spaces, underscores, and dashes.

    <?php
    
    use Krystal\Text\TextUtils;
    
    echo TextUtils::studly("hello world"); // HelloWorld
    echo TextUtils::studly("my_variable-name"); // MyVariableName

## Explode text

Splits text into sentences using standard punctuation and optional custom line breaks.

    <?php
    
    use Krystal\Text\TextUtils;
    
    $text = "Hello! How are you? Everything is fine.";
    $sentences = TextUtils::explodeText($text);
    print_r($sentences);

This will output

    Array
    (
        [0] => Hello!
        [1] =>  How are you?
        [2] =>  Everything is fine.
    )

## Multi explode

Splits a string into segments using multiple delimiters, optionally keeping the delimiters in the output.

    <?php
    
    use Krystal\Text\TextUtils;
    
    $text = "Hello! How are you? Everything is fine.";
    $parts = TextUtils::multiExplode($text, ['!', '?', '.']);
    print_r($parts);

This will output

    Array
    (
        [0] => Hello!
        [1] =>  How are you?
        [2] =>  Everything is fine.
    )
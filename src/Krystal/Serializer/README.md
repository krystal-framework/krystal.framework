Serializer
==========

**Serialization** is the process of converting a PHP variable — such as an array or object — into a string representation that can be **stored**, **transmitted**, or **reconstructed later**.

## Native

This format is **compact and efficient**, but is only suitable for **internal use** — it cannot be reliably parsed outside PHP.

    <?php
    
    use Krystal\Serializer\NativeSerializer;
    
    $serializer = new NativeSerializer();
    
    $data = ['user' => 'Alice', 'roles' => ['admin', 'editor']];
    
    // Serialize
    $encoded = $serializer->serialize($data);
    echo $encoded;
    // Example output: a:2:{s:4:"user";s:5:"Alice";s:5:"roles";a:2:{i:0;s:5:"admin";i:1;s:6:"editor";}}
    
    // Check if a string is serialized
    var_dump($serializer->isSerialized($encoded)); // true
    
    // Unserialize
    $decoded = $serializer->unserialize($encoded);
    print_r($decoded);
    // ['user' => 'Alice', 'roles' => ['admin', 'editor']]


**Notes**

-   The output is **not human-readable** and **PHP-specific**.
-   Avoid exposing it in APIs or storing it in shared databases.
-   Supports **any PHP value**, including closures and objects.


## JSON

It produces **human-readable** and **language-agnostic** strings suitable for configuration files, APIs, or logs.

    <?php
    
    use Krystal\Serializer\JsonSerializer;
    
    $serializer = new JsonSerializer();
    
    // Sample data
    $data = ['user' => 'Alice', 'roles' => ['admin', 'editor']];
    
    // Serialize to JSON
    $encoded = $serializer->serialize($data);
    echo $encoded;
    /*
    This will output:
    {
        "user": "Alice",
        "roles": [
            "admin",
            "editor"
        ]
    }
    */
    
    // Validate JSON
    var_dump($serializer->isSerialized($encoded)); // true
    
    // Unserialize (decode)
    $decoded = $serializer->unserialize($encoded);
    print_r($decoded);
    // ['user' => 'Alice', 'roles' => ['admin', 'editor']]


**Notes**

-   Converts objects to associative arrays by default.
-   UTF-8 safe and readable across systems.
-   Ideal for **API payloads**, **file-based storage**, or **interoperability**.
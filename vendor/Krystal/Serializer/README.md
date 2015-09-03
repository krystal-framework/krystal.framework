Serializer component
====================

This component provides a wrapper for serializing data. It support `JSON` and native PHP serializers.

# Available methods

## buildHash($array)

Builds a hash string of an array. Returns a hashed string.

## isSerializeable($var)

Determines whether it's possible to serialize the variable. Returns boolean value.

## isSerialized($string)

Determines whether a string has been serialized before (i.e looks like as a serialized one). Returns boolean value.

## serialize($var)

Serializes a variable. Returns a serialized string.

## unserialize($var)

Un-serializes the variable (i.e turns serialized string into its previous state). Returns an object or an array.

# Usage example

As mentioned in the beginning there are two adapters for data serialization:

    \Krystal\Serializer\NativeSerializer
    \Krystal\Serializer\JsonSerializer

They do implement aforementioned methods. Now let's look at basic example:

    use Krystal\Serializer\JsonSerializer;
    
    $serializer = new JsonSerializer();
    $string = $serializer->serialize(array('foo' => 'bar'));
    
    // Determine if the string is serialized
    $serializer->isSerialized($string); // true
    
    // Turn back into original representation
    $data = $serializer->unserialize($string); // array('foo' => 'bar')
    

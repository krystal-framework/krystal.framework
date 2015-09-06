Standard library
=============

This component provides a set of low-level tools.

# Virtual Entity

    \Krystal\Stdlib\VirtualEntity

If you prefer to store and access data via getters and setters, writing getters and setters will become tedious very quickly. Moreover, that leads to code duplication. Krystal provides a special service to address this problem, which is called `VirtualEntity`. When using it, you don't have to write setters and getters, because they're dynamic. Also dynamic setters do support method chaining.

For example, you can use it like this:

    <?php
    
    use Krystal\Stdlib\VirtualEntity;
    
    $user = new VirtualEntity();
    $user->setName('Dave')
         ->setAge(24);
    
    echo $user->getName(); // Dave
    echo $user->getAge(); // 24
    
    var_dump($user->getSomethingThatIsNotDefined()); // null

# Dumper

    \Krystal\Stdlib\Dumper::dump($var, $exit = true)

This is just a dumper. that prints data in nice looking format. There's only one method called `dump()`. As a first argument it accepts a variable itself, and the second argument defines whether to terminate a script right after output is done.

You can use it like this:

    <?php
    
    use Krystal\Stdlib\Dumper;
    
    $var = '.....';
    
    Dumper::dump($var); // Will output the content nicely


# ArrayUtils

    \Krystal\Stdlib\ArrayUtils

That's just a class with static methods to deal with arrays.

## Available methods

### arrayList($array, $key, $value)

Turns a row's array into a hash-like list. For example, consider this:

    <?php
    
    use \Krystal\Stdlib\ArrayUtils;
    
    $rows = array(
       array(
          'key' => 'name',
          'value' => 'Dave'  
      ),
      array(
         'key' => 'name',
         'value' => 'Jack'
      )
    );
    
    $list = ArrayUtils::arrayList($rows, 'key', 'value');

The resulting `$list` array would look like as following:

    array(
       'name' => 'Dave',
       'name' => 'jack'
    );


### arrayWithout($array, $keys)

Returns a filtered array by removing keys. For example:

    <?php
    
    use \Krystal\Stdlib\ArrayUtils;
    
    $array = array(
       'foo' => 'bar',
       'x' => 'y',
       'a' => 'b'
    );

    $result = ArrayUtils::arrayWithout($array, array('x', 'a'));

And the resulting array `$result` would look like as following:

    array(
      'foo' => 'bar'
    )


### search($haystack, $needle)

This method is similar to `array_search()`, expect that it searches recursively.

### arrayUnique($array)

Removes duplicate values from an array. The difference between `array_unqiue()` and this method is that can deal with multi-dimensional arrays as well, while `array_unqiue()` can't.

### hasAtLeastOneArrayValue($array)

This method determines whether at least one array's value is an array by type.

### isSequential($array)

Determines whether an array is sequential (i.e non-associative)

### isAssoc($array)

Determines whether an array is associative.
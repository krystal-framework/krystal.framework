
Parameter bag
============

This service simply abstracts array access to OOP-level by providing several methods for that. The methods are following:


# exists()

    boolean \Krystal\ParamBag\ParamBag::exists(string $key)

Checks whether a parameter exists in a stack.

# set()

    void \Krystal\ParamBag\ParamBag::set(string $key, mixed $value)

Appends a new parameter to the stack.

# get()

    mixed \Krystal\ParamBag\ParamBag::get(string $key, mixed $default = false)

Returns parameter's value if exists. If not, returns the value of the second argument.


# Example:

This is how you're gonna use it as a standalone tool, mostly:

    <?php
    
    $data = array(
       'foo' => 'bar'
    );
    
    $pb = new ParamBag($data);
    echo $pb->get('foo'); // Will output bar
    
    var_dump($pb->exists('foo'))// True
Dumper
======

    \Krystal\Stdlib\Dumper::dump($var, $exit = true)

This is just a dumper. that prints data in nice looking format. There's only one method called `dump()`. As a first argument it accepts a variable itself, and the second argument defines whether to terminate a script right after output is done.

You can use it like this:

    <?php
    
    use Krystal\Stdlib\Dumper;
    
    $var = '.....';
    
    Dumper::dump($var); // Will output the content nicely


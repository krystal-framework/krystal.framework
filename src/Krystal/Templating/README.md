Templating
==========

This component can be used to execute PHP code from a string. For example, if you need to store dynamic PHP code in a database, then you can execute 
that code by calling the `\Krystal\Templating\PhpEngine::execute()` method, passing a code itself as a first argument and a collection of variables as a second.
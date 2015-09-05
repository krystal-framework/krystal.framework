
# Instance manager

This component provides tools to deal with class instances. If you use it with the framework, then you probably don't need to use it at all, since its used internally by the framework.

However, if you'd like to use it as a standalone library, then let's learn about available tools.

# Service Locator

A service locator is just a container for objects. Those objects are usually service instances, hence it's called "Service Locator". 

    Krystal\InstanceManager\ServiceLocator::__construct($services = array())

On instantiation it can optionally accept an array of service instances.

## Available methods

### getAll()

Returns all registered service objects with their names. Returns an array.

### get($service)

Returns a service object by its associated name. Returns an object or throws `RuntimeException` if can't find a service by provided name.

### registerArray($instances)

Registers many pairs at once. Returns self. The `$instances`'s argument must look like this:

    array(
      'foo' => $foo,
      'bar' => $bar
    )
    
### register($name, $instance)

Registers service object by its name. Returns self.


### has($service)

Checks by name, whether a service has been registered before. Returns boolean value.

### remove($service)

Removes a service by its name. When trying to remove non-existing service, it'd issue a notice. Returns boolean value.


# Factory

    Krystal\InstanceManager\Factory

This tool would simplify building a family of objects, that follow PSR-0. A family of objects is nothing more than a shared directory for target classes. For example, consider that you have a directory with similar structure:

    /Vendor/Component/A.php
    /Vendor/Component/B.php
    /Vendor/Component/C.php

To simplify instantiation, you can use the Factory, like this:

    <?php
    
    use Krystal\InstanceManager\Factory;
    
    $factory = new Factory();
    
    // Define shared namespace
    $factory->setNamespace('Vendor/Component');
    
    $a = $factory->build('A');
    $b = $factory->build('B');

`build()` takes a second optional argument which defines an array of arguments to be passed to their constructors on instantiation.


# Dependency Injection Container (DiC)

    \Krystal\InstanceManager\DependencyInjectionContainer

Dependency injection container is nothing more than just a factory with callback functions that define how to build objects.

Let's start from a basic example:

    <?php
    
    use Krystal\InstanceManager\DependencyInjectionContainer;
    
    $dic = new DependencyInjectionContainer();
    $dic->register('db', function($self){
        return new Db();
    });
    
    $dic->register('bookMapper', function($self){
        // Grab a dependency we just registered
        $db = $self->get('db')
        return new BookMapper($db);
    });
    
    // The mapper is ready to be used
    $bookMapper = $dic->get('bookMapper');

As you can see, `register()` accepts two arguments: the first one is a name of a dependency to be registered, and the second is a callback function that holds a container itself as an argument. Essentially, the callback function must return an object. Later on, this object can be used to consume another dependencies.

The `get()` method returns an object by its registered name or throws `RuntimeException` on failure.
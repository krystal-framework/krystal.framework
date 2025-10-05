Auto-loader component
====================

> [!NOTE]
> This component should no longer be used. Use Composer instead.

This component is meant to provide common auto-loading functionality for classes, interface and traits. It can auto-load: PSR-0, PSR-4 compliant classes, as well as map-like ones.

# Configuration

As mentioned above, you have a configuration for PSR-0, PSR-4, and map class autoloaders. To configure that, open framework's configuration file (which is typically located at `/config/app.php`) and find a section called `autoload`. Inside this section you can configure autoloaders. So let's start from this.

## PSR-0

Krystal Framework itself follows classical PSR-0 structure. You will find that both framework and `module`'s folders are attached by default, like this:

		'autoload' => array(
			'psr-0'	=> array(
				dirname(__DIR__) . '/vendor',
				dirname(__DIR__) . '/module',
			)
		),

That means, that PSR-0 autoloader will use those paths as base directories. If you want to add another library that follows PSR-0, you can simply put it into vendor's directory without appending its path.

## PSR-4

That's a new convention to structure directories, Krystal and its modules directory  don't follow it at the moment. However, if you have a library that follows it, you can simply attach PSR-4 autooader to it like this:

		'autoload' => array(
			'psr-4'	=> array(
				'..prefix..' => '..path..',
			)
		),

The prefix defined a namespace prefix for the path.

## Class map

Some legacy PHP libraries don't follow neither PSR-0 and PSR-4 and have their own way of including classes. To add class autoloading support for them, you can manually declare paths for them. You can do so by creating a new section called `map` that contains an associative array with class names and their associated paths, like this:

		'autoload' => array(
			'map' => array(
			   'className' => 'path'
			)

Note, that the `path` must be without `.php` extension at the end.

# Using as a standalone library

You can use autoloading component as a standalone library. 

## PSR-0

First of all, you have to include it:

    require($basePath . '/Krystal/Autoloader/PSR0.php');

where `$basePath` is a path to a directory that contains Krystal Framework.

Second, you need to instantiate and configure it providing base directory path, like this:

    $loader = new PSR0;
    $loader->addDir('....'); 
    
    // Or if you have an array of directories
    
    $loader->addDirs(array('...', '...')); 
    
    // And finally
    $loader->register();

## PSR-4

Just like as in previous example, you have to include it:

    require($basePath . '/Krystal/Autoloader/PSR4.php');

where `$basePath` is a path to a directory that contains Krystal Framework.

And then you need to instantiate and register`PSR4` class provided a namespace with its associated base directory, like this:

    $loader = new PSR4();
    
    $loader->addNamespace('..prefix..', '..baseDir..');
    
    // Or if you have an array
    
    $loader->addNamespaces(array(
       '..prefix..' => '..baseDir..',
       '..anotherPrefix..' => '..anotherBaseDir..'
    ));
    
    $loader->register();


## Class map

First of all, you have to include it:

    require($basePath . '/Krystal/Autoloader/ClassMapLoader.php');

where `$basePath` is a path to a directory that contains Krystal Framework.

Second you need to instantiate `ClassMapLoader` providing a map to its contructor, like this:

    $map = array(
       'FooHandler' => '/path/to/Foo' // <-must be without php extension
    );
    
    $loader = new ClassMapLoader($map);
    $loader->register();
Modules
=======

A module itself is standalone application, that typically consists of controllers, assets, services and template views.

# Registering a new module

To register a new module, you have to create a folder named as a module itself with `Module.php` inside it. This file is called module definition. All module definition files, must extend  `\Krystal\Application\Module\AbstractModule` and must follow PSR-0. So each new module definition file should look like so:

    namespace YourModule;
    
    use Krystal\Application\Module\AbstractModule;
    
    class Module extends AbstractModule
    {
    	// ...
    }

## getRoutes()

Returns an array of routes for current module. To learn how to define routes, refer to controller documentation.

## getServiceProviders()

Returns an array of module services. To learn what a service is, refer to model documentation.

## getConfigData()

Returns user-defined configuration for the target module. You can use this, things such as module version. This method is optional.

## getTranslations($lang)

Returns an array of translations for the target module. The `$lang` argument is defined in configuration under `translator` section. This method is optional.

# Inherited methods:

Since module definition class extends `\Krystal\Application\Module\AbstractModule`, there are some useful inherited methods as well. You probably shouldn't use them in the definition class, but rather in controllers.

## getService($name)

Returns a service object by its associated name. That name must be registered in `getServiceProviders()`.

## getServices()

Returns an array of the target module services.

## hasService($name)

Determines whether service has been registered by its name.

## hasConfig($key = null)

If no arguments passed, it determines whether the target module has implemented `getConfigData()` and has returned a configuration array. Otherwise, it determines whether `$key` exists in target configuration.


# Working with modules

You would usually want to get a module service or its custom configuration in controllers. To do so, you can call `getModule()` on `moduleManager` property (which a framework service itself) providing a module name.

For example, if you want to get a service called `PostManager` in `News` module, you'd do it like this:

    public function someAction()
    {
    	// Grab the News module (i.e module definition object)
    	$module = $this->moduleManeger->getModule('News');
    
    	// Grab its registered service called "postManager"
    	$postManager = $module->getService('postManager');
    
    	// ....
    }

To determine a name of the current module, you can use `moduleName` property.

## Shortcut methods

There are two shortcut methods available in controllers.

# getModuleService($name)

Returns a service of the current module, when a controller is being executed. That's an equivalent to:

    $this->moduleManager->getModule($this->moduleName)->getService($name)

# getService($module, $name)

Returns a service from particular module.

That's an equivalent to:

    $this->moduleManager->getModule($module)->getService($name)


And that's all you need to know about modules!


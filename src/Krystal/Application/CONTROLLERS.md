Controllers
===========

A controller is just a standalone class that implements so-called "action methods" that respond to route matches. A route map itself must be defined in your `Module.php` in `getRoutes()` method. All controller classes must follow PSR-0 and must be located under `Controller` directories.

A typical directory structure, looks like so:

    module
     - Site
       - Controller
         - Main.php

Here two rules for all controllers:

[1] All your controller classes must extend `\Krystal\Controller\AbstractController`.
[2] Action methods must be public and must return a response string.




    namespace Site\Controller;
    
    use Krystal\Controller\AbstractController;
    
    class Main extends AbstractController
    {
        public function indexAction()
        {
            return 'Welcome';
        }
    }

As a best practice, controllers must be slim. That means, they only have to know how to call methods and pass resulting values to view. They should never process data, and contain complicated logic. If you have a logic, that must be in your model layer.

Routes
======

As mentioned before, routes are declared in `getRoutes()` method in your `Module.php`.


    <?php
    
    namespace Site;
    
    use Krystal\Module\AbstractModule;
    
    class Module extends AbstractModule
    {
        // ...
    
        public function getRoutes()
        {
            return array(
                '/' => array(
                    'controller' => 'Welcome@indexAction'
                )
            );
        }
    
        // ...
    }
    
    ?>

That means, when route `/` is matched, the controller under target module (which is `Site`) named `Welcome` will be instantiated and its `indexAction` method will be executed and its response will be returned automatically.

Route configuration
===================

There's only one thing that can be configured - default action, that's a method which gets executed when no route maches found. It can be found in configuration file (which is usually located at `/config/app.php`) under `components -> route` section. Here's how it looks like:

    // ...
    
    'router' => array(
        'default' => '...',
    ),
    
    // ...

To define a default method when no route match is found, simply define a controller as if you were defining it in route map, prepending module name. For example,

    'default' => 'Site:Welcome@notFoundAction'

Then `/Site/Controller/Welcome::notFoundAction` will be executed when no route matches are found.

# Route variables

It's very common to have variables in URI string. Route variables are defined in route map as `(:var)` keyword, like this:

    /page/(:var) => array(
        'controller' => 'Foo@indexAction'
    )

Then variables are automatically become available as arguments in controller actions.

    public function indexAction($id) // <- The value of (:var) will be passed here
    {
    
    }

If you define several variables, they will be passed as arguments exactly as in defined order.

# Triggering 404 error

To trigger 404 error manually, you can return `false` in controller's action. Once you do, then the default action will be executed. That is useful, if you want to handle call to invalid parameters.

For example, here's a typical triggering case:

    public function indexAction($id)
    {
        $id = '... do fetch some record ...';
    
        if (!$id) {
            // If record isn't valid, then trigger 404
            return false;
        } else {
            // Otherwise, process it...
        }
    }

# Controller shortcuts

There are several shortcut methods available in each controller.

## redirectToRoute($route)

Performs HTTP redirect to route's URL. The route itself must be framework-compliant, like `Site:Welcome@testAction`.

## forward($route, array $args = array())

Calls another controller's action from within current one. The second `$args` is an optional array of arguments to the action which is being called. Returns a response as a string. For example:

    public function someAction()
    {
        // Grab a response from another controller's action
        $response = $this->forward('Site:Welcome@testAction');
    }

## getWithAssetPath($path, $module = null)

Returns asset path appending target path. By default it assumes the current module to generate the path from. Passing second module name as a second argument, you can override it.

## getWithViewPath($path, $module, $theme)

Generates and returns a path to view folder on the file-system. The first `$path` argument is a path to be prepended, the second `$module` is a module name, and the third `$theme` is a theme name in views.
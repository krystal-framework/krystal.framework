View
====

The view service deals with template rendering work-flow and its available in controllers as `view` property. All templates must be located under `/{module}/View/Template/{theme}/`, where {module} is a name of particular module, and {theme} is a theme name which has been defined in configuration file. All template files must end with `.phtml` extension.

For example, suppose you have a template in `/module/Site/View/Template/home.phtml` and you want to render it in controller's `indexAction`. To do so, you'd simply call `render()` passing `home` as a first argument. That would look like so:

    public function indexAction()
    {
    	return $this->view->render('home');
    }

Obviously, the controller must belong to the same module.

# Configuration

The configuration stored under `view` key in `components`. It typically contains a nested array with the following keys:

- theme - a name of the theme to be used, when doing render.
- obfuscate - defines whether to compress an output or not.
- plugins - an array of asset plugins. A plugin itself should contain a name of a plugin as a key with and a nested array of definitions as a value. Path definitions should contain `scripts` and `stylesheets` with an array of paths.

For example, let's define `jquery` as a plugin:

    'view' => array(
    	'plugins' => array(
    		'jquery' => array(
    			'scripts' => array(
    				'@Site/js/jquery.min.js'
    			)
    			// jquery has no stylesheets, so we can omit that
    		)
    	)
    )

And then in any controller, we can simply load that plugin, like so:

    $this->view->getPluginBag()->load('jquery');

Note, that prefixing a module name with `@` is just defining a path to assets folder for that module, so that instead of writing:

    /module/Site/Assets/

you can simply write it as

    @Site/


## Available methods

You can call these methods on `view` service in controllers and in directly in templates.

## setComporess($compress)

Defines whether to compress outputting template at runtime.

## url($controller, ...)

Generates URL to given controllers action.

## addVariable($name, $value)

Adds a variable to template.

## addVariables($variables)

Add variables to the template. That must be an array with names with their associated values.

## getTheme()

Returns current theme name.

## asset($path, $module = null, $absolute = false)

Returns a full-qualified asset path. The optional second `$module` argument defined whether to use current one or permanent one. The third `$absolute` boolean argument defines whether to return absolute or full path.

## moduleAsset($path, $module = null, $absolute = false)

Returns a full-qualified asset path of a module itself (i.e the path to its `Assets` folder). The optional second `$module` argument defined whether to use current one or permanent one. The third `$absolute` boolean argument defines whether to return absolute or full path.

## setLayout($layout)

Sets the master template.

## disableLayout()

Disables the master template.

## hasLayout()

Determines whether a master template has been defined.

## templateExists($template)

Determines whether template exists.

## show($string, $translate = true)

Prints a string. If second argument is true, then it would try to translate it.

## render($template, array $vars = array())

Renders a template and returns it as a string. If a layout has been defined, it renders a template with defined layout.

## renderRaw($module, $theme, $template, array $vars = array())

Renders a template as a layout from a module. This is useful if you want to render a file for email-attachement. 
In fact, it's been added exactly for this purpose.

## loadBlock($block)

Loads a block. To learn more about defining blocks, refer to `BlockBag`.

## loadBlocks($array $blocks)

Loads many blocks at once.

## translate($string)

Returns a translated string.


# Plugin bag

A view plugin is just a name with associated stylesheets and scripts. If you want to include one plugin in several places, that would lead to code duplication, since you have to write paths in those places. Plugin bag comes to the rescue! It would help you to manage asset paths.
You can access its instance via `view` service (since its a part of view layer), by calling `getPluginBag()` method on it, that returns the `PluginBag`.

Typically, plugins are defined in configuration file and included on demain in controllers.

# Available methods

## appendStylesheet($stlysheet)

Append a stylesheet file (that must URL path).

## appendStylesheets(array $stylesheets)

Append many stylesheet files at once. That must an array of URL paths to them.

## getStylesheets()

Returns an array of defined stylesheets.

## appendScript($script)

Appends a script file. That must be a URL path.

## appendScripts($scripts)

Appends many scripts at once. That must an array of URL paths to them.

## getScripts()

Returns an array of defined script files.

## register($collection)

Registers a plugin at runtime. The `$collection` array itself must look like so:

    array(
    	'foo' => array(
    		'scripts' => array(
    			// ...
    		),
    		'stylesheets' => array(
    			// ...
    		)
    	)
    )

## load(array|string $plugin)

Loads a plugin or a collection of plugins.

# Block bag

A template block (also known as partial view) represents a reusable template fragment. For example, that can be pagination block that you want to be shared with all another templates. A block is usually rendered inside templates via `loadBlock()` method, but we didn't learn how to register them before using. So let's learn that right now.

Since the block bag is a part of view service, its instance can be accessed via `getBlockBag()` method on it.

# Available methods

## getBlockFile($name)

Returns a path of registered block by its name. If it can't find it, then a `LogicException` will be thrown.

## addStaticBlock($baseDir, $name)

Adds a static block to the stack. In case you want all modules to share one block, this method will register that block for you.
The first `$baseDir` argument is a path to directory where named block is stored (name shouldn't contain `phtml` extension).

## setBlocksDir($dir)

Defines a shared directory path where all blocks are stored.

## getBlocksDir()

Returns shared blocks directory.
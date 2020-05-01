CHANGELOG
=========

1.3
---

 * Added `field()` method to generate fields in `ViewManager` service
 * In `PluginBag` service fixed issue with broken link generation, when URL string contains `@`
 * In view service, added `loadPartialIfPossible()` method
 * In PluginBag, all appended scripts and stylesheets are now included only once by default
 * In PluginBag, methods that append scripts and stylesheets are now accept optional `$once` boolean parameter
 * Introduced conception of view widgets. A widget in templates can be rendered via `$this->widget()`
 * Make router match everything after ? mark, so that as of now, there's no need to create another route to grab GET variables
 * In router configuration section added `ssl`. Now HTTP to HTTPs redirection can be turned on via this parameter
 * The method `render()` in view service since now throws `LogicException` if empty template filename provided
 * In view since now the method `$this->url()` returns target string on failure, rather than false
 * In `\Krystal\Application\View\PluginBag` added `appendLastStylesheet()` and `appendLastStylesheets()` that always append last stylesheets
 * Make timezone optional in configuration
 * Since now shared `createUrl()` can build both query URLs and simple ones. It detects the type of array provided in arguments
 * In base controller's `bootstrap()` added `$action` argument which can be used to determined which action is about to be executed
 * In `\Krystal\Application\View\PluginBag` added `appendLastScript()` and `appendLastScripts()` that always append scripts last
 * Added extra constraint for module names. They must start from a capital letter and contain only alphabetic characters
 * Since now shared `toEntity()` method can handle more than one argument
 * Added `formAttribute` service
 * Ignore GD recoverable warnings on bootstrap
 * In view service added `createQueryUrl()` to generate URLs with query string
 * In `AppConfig` added extra methods to generate paths for module views and templates
 * In module manager service, added `getUnloadedModules()` to compare a collection of modules against current loaded ones
 * In `PluginBag` service added `clearScripts()` and `clearStylesheets()`
 * In shared controller, added `json()` shortcut method
 * In view service, added `mapUrl()`
 * In view service, added `hasVariable()` and `getVariable()`
 * Since now components are being registered without being checked for entries in the main configuration array
 * Renamed `Model\AbstractManager` to `Model\AbstractService`
 * Added conception of core modules. 
   They have a validation method, the framework can not be started without them, they can't be removed from file system via built-in handler
 * Forced to display errors instead of 500 Internal Server Error on PHP failures, when `production` setting is `true`
 * In `appConfig` service, added `getModuleCacheDir()` and `getModuleUploadsDir()`
 * In module manager service, added `removeFromFileSysem()`, `removeFromUploadsDir()` and `removeFromCacheDir()` methods
 * Added `KRYSTAL` constant. Its available as soon as application bootstraps
 * In `PartialBag` service dropped `setPartialsDir()` and `getPartialsDir()`. Replaced them with `addPartialDir()` and `addPartialDirs()`
 * Renamed conception of `block` to `partials`. Updated its corresponding API
 * Added `removeFromFileSysem()` in module manager service
 * Added `authAttemptLimit` core service
 * Made `paramBag` service available in module definition classes
 * Fixed missing controller name for halting cases in `UrlBuilder`
 * Made view rendering exception a bit clear. It now displays a file being rendered with its base path
 * Dropped `Krystal\Application\View\AssetPath` abstraction. Moved its implementation to `Krystal\Application\View\AssetPath\PluginBag::normalizeAssetPath()`
 * Added `Krystal\Application\View\BlockBag::addStaticBlocks()` to add a collection
 * In `Krystal\Application\View\BlockBag::addStaticBlock()` forced to return the chain
 * Added `createUrl()` method in shared controller
 * Removed view shortcut methods from shared controller. Since now they must be called from the `view` service only.
 * Dropped `getResolverThemeName()`, `getResolverModuleName()` in shared controller. 
   Since now they must be altered right from view service directly, when needed
 * Made `renderRaw()` in View service not to override initial state
 * Added `hasVariables()` in view
 * Dropped extra abstraction for view resolver
 * Renamed `App` to `Kernel`
 * Dropped extra `TemplateView` abstraction in internals of View layer
 * Dropped loading messages under initialization level. Forced to load translation messages from controllers.
   Added shared `loadTranslations()` method to load or reload translation messages
 * Added `createMapper()` shortcut methods in `AbstractModule` and `AbstractController`
 * Added `translateArray()` shortcut in `\View\ViewManager`
 * Added shared `createValidator()` shortcut for controllers
 * Dropped own autoloading support. Since now autoloading is managed via Composer

1.2
---

 * Removed ability to configure php.ini at runtime 

1.1
---

 * Added interception filter's implementation
 * Separated `App\bootstrap()` with `App\run()`

1.0
---

 * First public version
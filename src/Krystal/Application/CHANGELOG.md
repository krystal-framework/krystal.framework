CHANGELOG
=========

1.3
---

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
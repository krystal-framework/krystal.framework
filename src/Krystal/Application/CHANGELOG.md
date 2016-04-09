CHANGELOG
=========

1.3
---

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
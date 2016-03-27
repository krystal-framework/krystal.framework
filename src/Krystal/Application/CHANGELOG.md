CHANGELOG
=========

1.3
---

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
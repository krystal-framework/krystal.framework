CHANGELOG
=========

1.3
---

 * Forced to throw `\UnderflowException` when defining a setter with no arguments in `VirtualEntity`
 * Fixed issue with handling `null` values in `VirtualEntity`
 * Changed date format to Y-m-d h:i:s in exception handler
 * Removed framework version from exception's template
 * Added `keysExist()` in `Stdlib\ArrayUtils`
 * Added `arrayOnlyWith()` in `Stdlib\ArrayUtils`
 * Forced to throw exceptions when trying to retrieve a getter for undefined property in `VirtualEntity`

1.2
---
 
 * Added `arrayCombibe()` in `Stdlib\ArrayUtils`
 * Added new method `assocPrepend()` in `Stdlib\ArrayUtils`

1.1
---

 * Slightly improved internals

1.0
---

 * First public version
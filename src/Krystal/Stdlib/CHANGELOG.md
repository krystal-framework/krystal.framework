CHANGELOG
=========

1.3
---

 * Since now `Dumper::dump()` renders objects if they implement `__toString()`
 * `VirtualEntity` since now can be treated as array and its properties can be accessed via snake case
 * Added `ArrayCollection` that simplifies work with static collections
 * Added `arrayDropdown()` in `Stdlib\ArrayUtils` to drop result-sets into partitions and prepare them for dropdowns
 * Added `arrayPartition()` in `Stdlib\ArrayUtils` to drop result-sets into partitions
 * Added `arrayRecovery()` in `Stdlib\ArrayUtils` to recover missing keys
 * Added `valuefy()` in `Stdlib\ArrayUtils` to duplicate either keys or values in arrays
 * Added `getProperties()` in `VirtualEntity`
 * Added `sumColumnsWithAverages()` in `Stdlib\ArrayUtils` to count a sum with averages
 * Added `roundValues()` in `Stdlib\ArrayUtils` to filter array values recursively with precision
 * Added `filterValuesRecursively()` in `Stdlib\ArrayUtils` to apply a callback function that filter array values recursively
 * Added `sumColumns()` in `Stdlib\ArrayUtils` to count the sum of column right from a data array
 * Added `arrayColumns()` in `Stdlib\ArrayUtils` to extract column names
 * Added `columnSum()` in `Stdlib\ArrayUtils` to count the sum of columns
 * Added `filterArray()` in `Stdlib\ArrayUtils` to apply a callback function to each memeber of array
 * Added `mergeWithout()` in `Stdlib\ArrayUtils`
 * Moved away exception handler to standalone component
 * Added optional support for filters `VirtualEntity` when defining a setter
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
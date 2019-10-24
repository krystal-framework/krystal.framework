CHANGELOG
=========

1.3
---

 * Dropped option to override a placeholder. Made it as a constant internally
 * By default, URL is now generated automatically from current URI string
 * Force to throw `\LogicException` if defined URL string doesn't have a placeholder
 * Made page placeholder configurable. It can be passed as a second argument to the constructor
 * Added `toArray()` and `toJson()` methods

1.1
---
 
 * Improved internal code base
 * Added support for automatic URL generation for pages. 
   Added getNextPageUrl() and getPreviousPageUrl() accordingly
 * Deprecated initial tweaker methods in favour of tweak().

1.0
---

 * First public version
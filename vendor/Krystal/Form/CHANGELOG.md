CHANGELOG
=========

1.1
---

 * Removed all helpers which render HTML elements. 
   That is a bad practise, because it violates Separation Of Concerns

 * Added `NodeElement` class. 
   Now HTML elements inside recursive functions can be easily constructed without native `DOMDocument`
 
 * Moved breadcrumb's functionality into `Form` component from `Application\View`

 * Improved `HTMLHelper`. Now it has more "useful" methods
 
1.0
---

 * First public version
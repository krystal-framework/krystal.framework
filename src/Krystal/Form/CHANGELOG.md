CHANGELOG
=========

1.2
---

 * Added ability to select multiple option nodes in `\Form\Element\Select`
 * Renamed providers to gadgets since this name is more appropriate
 * Now `Form\NodeElement::addProperty()` renders strictly-compliant properties, like `prop="prop"`
 * `Form\NodeElement` can now render properties on demand when setting them as attributes
 * Added `Form\Element`. Now elements can be rendered via its shortcut methods
 * Added builder for  HTML5 `range` inputs
 * Added builder for button elements
 * Breadcrumb service's add() method now returns self instead of void
 * Added addOne() to breadcrumb bag. That makes it much easier to add single breadcrumbs
 * Added shortcut methods for attributes in `NodeElement`
 * Now it's not possible to set the same attribute or property twice on elements

1.1
---

 * Removed all helpers which render HTML elements
 * Added `NodeElement` class. Now HTML elements inside recursive functions can be easily constructed without native `DOMDocument` 
 * Moved breadcrumb's functionality into `Form` component from `Application\View`
 * Improved `HTMLHelper`. Now it has more useful methods
 
1.0
---

 * First public version
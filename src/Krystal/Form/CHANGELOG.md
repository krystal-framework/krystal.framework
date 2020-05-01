CHANGELOG
=========

1.3
---

 * Added field generator
 * In `Element` added `fileData()` - file input with its hidden field generator
 * Improved internal attribute filter in `NodeElement`
 * Added HTML5 video tag generator via `Element::video()` method
 * Added HTML5 audio tag generator via `Element::audio()` method
 * Removed BooleanWidget. This is no longer required, since Grid itself can generate boolean values.
 * In `Element` added `object()` - object element generator
 * In `Element` added `time()` input generator
 * In `Element` added `icon()` shortcut method
 * Since now automatically escape attribute values when rendering elements via `Element` class
 * In `BreadcrumbBag` added `getCount()` that returns a count of available breadcrumbs
 * `Krystal\Form\Element::select()` can now select all nodes if `*` provided as a selector for active nodes
 * `Krystal\Form\Element::select()` can now accept optional visitor for option nodes
 * Added Form Attribute sub-component
 * Removed `DropdownWigdet`
 * Added optional 5-th `$prompt` argument, that can a prompting text in `Element::select()`
 * Added `appendChildren()` in `NodeElement` to append several child nodes at once
 * Added `appendChildWithText()` in `NodeElement`
 * Added `LastCategoryKeeper` gadget
 * Added `Element::dynamic()` renderer
 * Added 3 new methods in `BreadcrumbBag` service to simplify work with names: `getNames()`, `getFirstName()`, `getLastName()`
 * Added link() method in `\Form\Element` to generate links
 * Added label() method in `\Form\Element` to generate labels

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
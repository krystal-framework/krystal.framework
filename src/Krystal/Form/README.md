Form component
==============

The form component provides several tools to simplify work with HTML forms. Let's explore them one by one.


# Flash bag

Also known as *Flash Messenger*. It sets a message only for a single request. After request is done, the message is removed automatically. Typically, flash bag is useful for setting messages about successes or failures. For example, right after user created an article, you would want to notify him about that it has been created successfully. Flash bag is specially designed for that. The service has 3 methods: `get()`, `has()` and `set()`.

## Setting a message

You would do that in controller actions only. To do so you'd call a service (which is available by default) calling the `set()` method on it. As an example, it would look like as following:

    public function addAction()
    {
         // ... Do add here
         $this->flashBag->set('success', 'An article has been created successfully!');
    }

## Rendering a message

You would usually want to render flash messages in template views. But before doing so, you'd check if a message exists. As a corresponding example to the previous one, the checking would look like this:

    <?php if ($flashBag->has('success')): ?>
       <p><?php echo $flashBag->get('success'); ?></p>
    <?php endif; ?>

In real-world scenario, you of course would want to style it in elegant way. But for the simplicity, a flash message is wrapped around `<p>..</p>`.


# Form element generators

It's much easier to generate input elements than write them manually all the time. Krystal provides a tool to do that.

The typical usage might look like this:

    <?php
    
    use Krystal\Form\Element;
    
    ?>
    
    <div>
        <?php echo Element::text('login', 'john'); ?>
        <?php echo Element::password('password', 'john'); ?>
    </div>

That will generate the following result:

    <div>
       <input type="text" name="login" value="john" />
       <input type="password" name="password" value="john" />
    </div>

## Available methods

### image()

    \Krystal\Form\Element::image($src, array $attributes = array())

Generates image element.

### password()

    \Krystal\Form\Element::password($name, $value, array $attributes = array())

Generates password input.

### url()

    \Krystal\Form\Element::url($name, $value, array $attributes = array())
    
Generates URL input.

### range()

    \Krystal\Form\Element::range($name, $value, array $attributes = array())
    
Generates range input element.

### number()

    \Krystal\Form\Element::number($name, $value, array $attributes = array())

Generates number input element.

### hidden()

    \Krystal\Form\Element::hidden($name, $value, array $attributes = array())

Generates hidden input element.

### email()

    \Krystal\Form\Element::email($name, $value, array $attributes = array())

Generates email input element.

### date()

    \Krystal\Form\Element::date($name, $value, array $attributes = array())

Generates date input element.

### color()

    \Krystal\Form\Element::color($name, $value, array $attributes = array())

Generates color input element.

### text()

    \Krystal\Form\Element::text($name, $value, array $attributes = array())

Generates text input element.

### textarea()

    \Krystal\Form\Element::textarea($name, $text, array $attributes = array())

Generates textarea element.

### select()

    \Krystal\Form\Element::select($name, array $list = array(), $selected, array $attributes = array())

Generates select element with nested option elements as well.

### file()

    \Krystal\Form\Element::file($name, $accept = null, array $attributes = array())

Generates file input element.

### checkbox()

    \Krystal\Form\Element::checkbox($name, $checked, array $attributes = array(), $serialize = true)

Generates checkbox element.

### radio()

    \Krystal\Form\Element::radio($name, $value, $checked, array $attributes = array())

Generates radio element.

### button()

    \Krystal\Form\Element::button($text, array $attributes = array())
    
Generates button element.

### submit()

    \Krystal\Form\Element::submit($text, array $attributes = array())

Generates submission button.

### reset()

    \Krystal\Form\Element::reset($text, array $attributes = array())

Generates reset button.


# Node element

    \Krystal\Form\NodeElement

This tool can be used to build HTML/XML elements dynamically. For example, you can use it when building a drop-down menu inside a recursive function. A building process itself might look like this:

    use Krystal\Form\NodeElement;
    
    $checkbox = new NodeElement();
    $checkbox->openTag('input')
             ->addAttribute('type', 'checkbox')
             ->addAttribute('id', 'my-checkbox')
             ->finalize(); 

    echo $checkbox->render();

This will output:

    <input type="checkbox" id="my-checkbox">

## Available methods

All methods can be chained, since internally they return `$this`, expect `render()` method.

### openTag()

    \Krystal\Form\NodeElement::openTag($tagName)

Opens a tag.

### closeTag()

    \Krystal\Form\NodeElement::closeTag($tagName = null)

Closes currently opened tag. 
In case you want to close a tag without opening it, provide the name of it as an argument - this can useful in recursive functions only.

### addProperty()

    \Krystal\Form\NodeElement::addProperty($property)

Adds a property. As an example, it can be `checked`, `selected` or even Angular's directive such as `ng-bing`.

### addProperties()

    \Krystal\Form\NodeElement::addProperties(array $properties)

Adds many properties at once.

### hasProperty()

    \Krystal\Form\NodeElement::hasProperty($property)

Checks whether a property has been set. Returns boolean value.

### getProperties()

    \Krystal\Form\NodeElement::getProperties()

Returns an array of defined properties.

### addAttribute()

    \Krystal\Form\NodeElement::addAttribute($key, $value)

Adds an attribute with its value. For example, you can set a name for the element using this method.

### addAttributes()

    \Krystal\Form\NodeElement::addAttributes(array $attributes)

Adds many attributes at once. You'd provided an array of arguments, that looks like so:

    $element->addAtrribites(array(
       'name' => 'foo',
       'id' => 'bar'
    ));

### clear()

    \Krystal\Form\NodeElement::clear()

Clears the stack.

### finalize()

    \Krystal\Form\NodeElement::finalize($singular = false)

Appends either `>` or `/>`. If `$singular` is `true` then it'd append `/>`, otherwise `>`. As you might already guessed, it should be called when closing tags.

### setText()

    \Krystal\Form\NodeElement::setText($text, $finalize = true)

Sets a text between tags. If the second `$finalize` argument is `true`, it would finalize currently opened tag, before setting a text. 

### appendChild()

    \Krystal\Form\NodeElement::appendChild(NodeElement $node)

Appends a child element. The child itself must be instance of `NodeElement`.

### getAttribute()

    \Krystal\Form\NodeElement::getAttribute($attribute, $default = false)

Returns attribute value. In case the target attribute isn't defined, then the value of second argument is returned.

### hasAttribute()

    \Krystal\Form\NodeElement::hasAttribute($attribute)

Checks whether an attribute has been set. Returns boolean value.

### getAttributes()

    \Krystal\Form\NodeElement::getAttributes()

Returns an array of defined attributes and their associated values.
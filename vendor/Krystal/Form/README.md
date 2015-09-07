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


# Breadcrumb bag

This service helps you to manage breadcrumbs on your pages. The view service has `getBreadcrumbBag()` method that returns an instance of breadcrumb bag, like this:

    $bb = $this->view->getBreadcrumbBag();

## Adding breadcrumbs

To add breadcrumbs, you have to provide an array of arrays defining `link` and `name` parameters, like this:

    $this->view->getBreadcrumbBag()->add(array(
        array(
           'link' => '/'
           'name' => 'Home page'
        ),
        array(
           'link' => '/about',
           'name' => 'About us'
        )
    ));

As a rule of thumb, you'd usually do that in controller actions, before rendering a template.

## Rendering breadcrumbs

Usually, the rendering is done in template layout file. The service method `getBreadcrumbs()` returns an array of breadcrumb entities, where each each entity has 4 methods: `getLink()`, `getName()` , `isActive()` and `isFirst()`. To determine whether bag is empty or not, you'd use `has()` method on it, which returns a boolean, before rendering them. As an example:

    <?php if ($this->getBreadcrumbBag()->has()): ?>
    
     <ul>
        <?php foreach ($this->getBreadcrumbBag()->getBreadcrumbs() as $bc):  ?>
        <?php if ($bc->isActive()): ?>
        <li class="active"><?php echo $bc->getName(); ?></li>
        <?php else: ?>
        <li><a href="<?php echo $bc->getLink(); ?>"><?php echo $bc->getName(); ?></a></li>
        <?php endif; ?>
       <?php endforeach; ?>
     </ul>
    
    <?php endif; ?>

A last breadcrumb is consider active. That's what exactly `isActive()` method determines.

## Extra

Sometimes you might want to remove only first breadcrumb entity. To do so, you can use `removeFirst()` on the bag service.


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

### openTag($tagName)

Opens a tag.

### closeTag($tagName = null)

Closes currently opened tag. 
In case you want to close a tag without opening it, provide the name of it as an argument - this can useful in recursive functions only.

### addProperty($property)

Adds a property. As an example, it can be `checked`, `selected` or even Angular's directive such as `ng-bing`.

### addAttribute($key, $value)

Adds an attribute with its value. For example, you can set a name for the element using this method.

### addAttributes($attributes)

Adds many attributes at once. You'd provided an array of arguments, that looks like so:

    $element->addAtrribites(array(
       'name' => 'foo',
       'id' => 'bar'
    ));

### clear()

Clears the stack.

### finalize($singular = false)

Appends either `>` or `/>`. If `$singular` is `true` then it'd append `/>`, otherwise `>`. As you might already guessed, it should be called when closing tags.

### setText($text, $finalize = true)

Sets a text between tags. If the second `$finalize` argument is `true`, it would finalize currently opened tag, before setting a text. 


### appendChild(NodeElement $node)

Appends a child element. The child itself must be instance of `NodeElement`.


# HTML helper

    \Krystal\Form\HtmlHelper

That's just a class with static methods, that help with various task in template views.

## Available methods

### wrapOnDemand($condition, $tag, $content)

Wraps a content into a tag, if condition is true. If not, returns a regular text as provided. For example, if you want to wrap a text into `<em></em>` if condition is true, you'd use it like this:

    <?php
    
     use Krystal\Form\HtmlHelper;
    
     $cond = '....'
    
     HtmlHelper::($cond, 'em', 'Some text');
    
     ?>

### makeReadOnlyOnDemand($condition)

Outputs `readonly` property if condition is true-like.

### selectOnDemand($condition)

Outputs `selected` property if condition is true-like.

### checkOnDemand($condition)

Outputs `checked` property if condition is true-like.

### addOnDemand($condition, $text)

Outputs a text if condition is true-like.

### addClassOnDemand($condition, $value)

Outputs a class with provided value, if condition is true-like.


### addAttrOnDemand($condition, $attr, $value)

Outputs an attribute with a value, if condition is true-like.

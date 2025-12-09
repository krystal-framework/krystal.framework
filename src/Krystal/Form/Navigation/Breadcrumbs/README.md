Breadcrumbs
===========

Breadcrumbs help visitors understand their current location within your site.   This component is designed to make breadcrumb management simple and flexible.

---

## Adding breadcrumbs

Breadcrumbs are typically managed in both your controller and your view.  

To add breadcrumbs in a controller, you can use method chaining:

    public function aboutAction()
    {
        $this->view->getBreadcrumbBag()
                   ->addOne('Home page', '/')
                   ->addOne('About us'); // Last breadcrumb has no link (it is active)
    }

You can also add breadcrumbs using an array — recommended when generating them dynamically (e.g., building from a tree):

    public function aboutAction()
    {
        $breadcrumbs = [
            [
                'name' => 'Home page',
                'link' => '/'
            ],
            [
                'name' => 'About us'
            ]
        ];
    
        $this->view->getBreadcrumbBag()->add($breadcrumbs);
    }


## Rendering beadcrumbs

After populating the breadcrumb bag in your controller, you can render the breadcrumbs in the view.  

There are two primary approaches:

### Via widget

This is the simplest and preferred method.  In your template:

    <?php
    
    use Krystal\Widget\Breadcrumbs\BreadcrumbWidget;
    
    ?>
    
    <nav>
        <?= $this->widget(new BreadcrumbWidget()); ?>
    </nav>

Example output:

    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active">About us</li>
    </ol>

You can customize default classes using the widget options:

    <?= $this->widget(new BreadcrumbWidget([
        'ulClass'    => 'custom <ul> class',
        'itemClass'  => 'custom <li> class',
        'linkClass'  => 'custom <a> class'
    ])); ?>

### Rendering manually

If you need completely custom markup, you may manually iterate through the breadcrumb array:

    <?php if ($this->view->getBreadcrumbBag()->has()): ?>
        <?php foreach ($this->view->getBreadcrumbBag()->getBreadcrumbs() as $breadcrumb): ?>
    
            <?php $breadcrumb->isFirst();   // Returns boolean ?>
            <?php $breadcrumb->getName();   // Returns breadcrumb name ?>
            <?php $breadcrumb->getLink();   // Returns breadcrumb link (string or null) ?>
            <?php $breadcrumb->isActive();  // Returns true if this is the last (active) item ?>
    
        <?php endforeach; ?>
    <?php endif; ?>

## Additional methods

Additional methods cover extra use cases and provide more insight into breadcrumb data.

The `BreadcrumbBag` instance (`$this->view->getBreadcrumbBag()`) supports the following utility methods:

`removeFirst()`

Removes the first breadcrumb

`clear()`

Removes all breadcrumbs

`getNames()`

Returns an array of all breadcrumb names

`getFirstName()`

Returns the name of the first breadcrumb

`getLastName()`

Returns the name of the last breadcrumb

`getCount()`

Returns the total number of breadcrumbs
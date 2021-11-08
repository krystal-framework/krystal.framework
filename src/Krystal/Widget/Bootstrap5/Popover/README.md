
Popover
=======

This widget provides implementation of Bootstrap 5 Popover component.

Usage example
----

Here's the basic usage example:

    <?php
    
    use Krystal\Widget\Bootstrap5\Popover\PopoverMaker;
    
    $popover = new PopoverMaker('Button text', [
        'data-bs-content' => 'And here is some amazing content. It is very engaging. Right?',
        'data-bs-title' => 'Popover title',
        'cssClass' => 'btn btn-lg btn-danger'
    ]);
    
    echo $popover->render();

The following will render HTML like this:

    <a class="btn btn-lg btn-danger text-nowrap" tabindex="0" role="button" data-bs-title="Popover title" data-bs-content="And here is some amazing content. It is very engaging. Right?" data-placement="left" data-bs-animation="true" data-bs-container="body" data-bs-delay="0" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="false" data-bs-selector="false" data-bs-boundary="clippingParents" data-bs-offset="[0, 8]">Button text</a>

Available options
----

You can override most default options, but some of them should be handled in JavaScript manually. For more details please refer to its [official documentation](https://getbootstrap.com/docs/5.1/components/popovers/).

| Option            | Description                                                                                            | Default value   |
|-------------------|--------------------------------------------------------------------------------------------------------|-----------------|
| data-bs-animation | Apply a CSS fade transition to the popover                                                             | true            |
| data-bs-container | Appends the popover to a specific element                                                              | body            |
| data-bs-delay     | Delay showing and hiding the popover (ms) - does not apply to manual trigger type                      | 0               |
| data-bs-toggle    | Dedicated selector when initializing component via JavaScript                                          | popover         |
| data-bs-trigger   | How popover is triggered - click \| hover \| focus \| manual                                           | hover           |
| data-bs-html      | Insert HTML into the popover. If false, innerText property will be used to insert content into the DOM | false           |
| data-bs-selector  | If a selector is provided, popover objects will be delegated to the specified targets.                 | false           |
| data-bs-boundary  | Overflow constraint boundary of the popover (applies only to Popper's preventOverflow modifier).       | clippingParents |
| data-bs-offset    | Offset of the popover relative to its target.                                                          | [0, 8]          |
| data-bs-content   | Popover content. HTML is supported.                                                                    |                 |
| data-bs-title     | Popover title                                                                                          |                 |
| data-bs-placement | How to position the popover - auto \| top \| bottom \| left \| right.                                  | left            |
| cssClass          | Custom CSS class to be applied to target element                                                       |                 |


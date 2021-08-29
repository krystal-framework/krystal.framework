Bootstrap 5 Accordion Widget
==========

The accordion widget renders Bootstrap5-compliant component. It's very simple and super easy to use and optimized internally. The implementation has been done according to [official Bootstrap 5 documentation](https://getbootstrap.com/docs/5.1/components/accordion/).

The structure
----

An instance of `\Krystal\Widget\Bootstrap5\Accordion\AccordionMaker` takes two arguments: an array of items and array of options. Each array of single item contains the following keys:

`header`

A string containing title for the card. It can also accept a callback function returning title string.

`body`

A string containing content for the the card.


Usage
----

    <?php
    
    use Krystal\Widget\Bootstrap5\Accordion\AccordionMaker;
    
    $items = [
        [
            'header' => 'Accordion Item #1',
            'body' => "This is the first item's accordion body"
        ],
    
        [
            'header' => 'Accordion Item #2',
            'body' => "This is the second"
        ]
    ];
    
    $options = [
        'flush' => false,
        'always_open' => false,
        'first_open' => true
    ];
    
    $accordion = new AccordionMaker($items, $options);
    echo $accordion->render();

The above example outputs:

    <div class="accordion" id="accordion-1630243892">
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading-0"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-0" aria-expanded="true" aria-controls="collapse-0">Accordion Item #1</button></h2>
            <div id="collapse-0" class="accordion-collapse collapse show" aria-labelledby="heading-0" data-bs-parent="#accordion-1630243892"><div class="accordion-body">This is the first item's accordion body</div></div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading-1">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1" aria-expanded="false" aria-controls="collapse-1">Accordion Item #2</button>
            </h2>
            <div id="collapse-1" class="accordion-collapse collapse" aria-labelledby="heading-1" data-bs-parent="#accordion-1630243892"><div class="accordion-body">This is the second</div></div>
        </div>
    </div>

Callback function in header
-----
Sometimes, you might want to use index value to create counter dynamically. In case of this, just provide callback function, which takes a single argument `$index`. Consider this:

    $items = [
        [
            'header' => function($index){
	          return 'Accordion Item #' . $index + 1;
             },
            'body' => "This is the first item's accordion body"
        ],
    
        [
            'header' => function($index){
	          return 'Accordion Item #' . $index + 1;
             },
            'body' => "This is the second"
        ]
    ];

In this case, the counter is always incremented once its iterated over the next item.


Options
-----

Note, that all parameters are optional.

`flush`
Boolean value. Whether to remove default styling. The default value is `false`.


`always_open`
Boolean value. Whether to close another items when another one is clicked. The default value is `false`.

`first_open`

Boolean value. Whether to open first item on load. The default value is `true`.



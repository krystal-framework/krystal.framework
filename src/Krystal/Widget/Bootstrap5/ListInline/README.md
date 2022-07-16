Floating form
----

This widget generates inline list with optional links.

# Usage example

    <?php
    
    use Krystal\Widget\Bootstrap5\ListInline\ListMaker;
    
    $items = [
        [
            'text' => 'Facebook'
        ],
    
        [
            'text' => 'Google'
        ],
    
        [
            'link' => 'https://microsoft.com',
            'text' => 'Microsoft',
            'blank' => true // Boolean value. Whether to open link in new window or not
        ]
    ];
    
    $lm = new ListMaker($items);
    echo $lm->render();

This will generate the following output:

    <ul class="list-inline">
        <li class="list-inline-item">Facebook</li>
        <li class="list-inline-item">Google</li>
        <li class="list-inline-item"><a href="https://gmail.com">Gmail</a></li>
    </ul>

You might want to append extra CSS classes to list nodes. You can supply an array of extra classes for each element as a second argument on instantiation:

    $classes = [
        'ul' => 'w-50',
        'li' => 'fw-bold',
        'a' => 'text-decoration-none'
    ];
    
    $lm = new ListMaker($items, $classes);
    echo $lm->render();


This will generate the following output:

    <ul class="list-inline w-50">
        <li class="list-inline-item fw-bold">Facebook</li>
        <li class="list-inline-item fw-bold">Google</li>
        <li class="list-inline-item fw-bold"><a class="text-decoration-none" href="https://gmail.com">Gmail</a></li>
    </ul>
    
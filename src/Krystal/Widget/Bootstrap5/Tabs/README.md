This widget renders Bootstrap 5-compliant tabs. A basic usage is the following:

    <?php
    
    use Krystal\Widget\Bootstrap5\Tabs\Tabs;
    
    $items = [
        [
            'name' => 'My tab',
            'text' => 'Some text'
        ],
        
        [
            'name' => 'Another tab',
            'text' => 'Some another text'
        ]
    ];
    
    $tabs = new Tabs($items);
    
    echo $tabs->render();

This will render tabs with their corresponding content. 

In case you require to render navigation items and tabsseparately, you can use two dedicated methods to do that.

    echo $tabs->renderNav(); // Render navigation only
    echo $tabs->renderTabs(); // Render tabs only

In case you want to omit tabs that have content, you can input `false` as a second argument in widget's contructor:

    // This will render only non-empty items (i.e the ones that have non-empty 'text' key item 
    $tabs = new Tabs($items, false);
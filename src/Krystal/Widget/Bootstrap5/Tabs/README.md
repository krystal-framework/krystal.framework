# Tabs widget
This widget renders Bootstrap 5-compliant tabs.

## Basic usage
 A basic usage is the following:
 
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

## Omit empty tabs

In case you want to omit tabs that have content, you can input `false` as a second argument in widget's contructor:

    // This will render only non-empty items (i.e the ones that have non-empty 'text' key item 
    $tabs = new Tabs($items, false);

## Fade effect

A fade effect is added by default. In case you want to drop it, you can input `false` argument when doing a render in these methods:

    echo $tabs->render(false); // Drop fade effect
    // Or
    echo $tabs->renderTabs(false); // Render tabs only with fade effect
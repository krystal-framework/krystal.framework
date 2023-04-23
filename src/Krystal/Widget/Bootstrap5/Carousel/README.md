
Bootstrap 5 Carousel Widget
==========

The carousel widget renders Bootstrap5-compliant component. It's very simple and super easy to use and optimized internally. The implementation has been done according to [official Bootstrap 5 documentation](https://getbootstrap.com/docs/5.1/components/carousel/).

The structure
----

An instance of `\Krystal\Widget\Bootstrap5\Carousel\CarouselMaker` takes two arguments: an array of slides and array of options. Each array of single slide contains the following keys:

`src`
The path to an image. Required parameter.

`alt`
The optional alt attribute for the image.

`caption`

Optional parameter. Either string containing HTML for caption element or an array with `title` and `description` keys.
It might also contain `button` key with `href`, `text`, `class`, `self` keys for button link. The `self` is a boolean key indicating whether a link should be opened in new window or not.

`interval`

The individual interval for each slide in milliseconds. Optional parameter.


Usage
---

Here's a basic usage example:

    <?php
    
    use Krystal\Widget\Bootstrap5\Carousel\CarouselMaker;
    
    $slides = [
        [
            // Slide without caption
            'src' => 'https://via.placeholder.com/800x600?text=1',
        ],
    
        [
            // Slide with caption
            'src' => 'https://via.placeholder.com/800x600?text=2',
            'caption' => [
                'title' => 'Hello there',
                'description' => 'The slide'
            ]
        ],
        
        [
            // Slide with caption and button link
            'src' => 'https://via.placeholder.com/800x600?text=3',
            'caption' => [
                'title' => 'Hello there',
                'description' => 'The slide with clickable button',
                'button' => [
                    'class' => 'btn btn-primary', // Optional. By default 'btn btn-primary' is used.
                    'href' => 'http://example.com',
                    'text' => 'Learn more',
                    'self' => true // Optional. By default true. If false, then will be opened in a new window.
                ]
            ]
        ]
    ];
    
    $options = [
        'controls' => true,
        'indicators' => true,
        'fade' => true
        // You can put another options as well, see below
    ];
    
    $carousel = new CarouselMaker($slides, $options);
    echo $carousel->render();

Which renders HTML like this:

    <div id="carousel-id-1630232710" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-touch="true" data-bs-interval="5000" data-bs-keyboard="true" data-bs-pause="hover">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carousel-id-1630232710" data-bs-slide-to="0" class="active" aria-current="true"></button>
            <button type="button" data-bs-target="#carousel-id-1630232710" data-bs-slide-to="1" class="" aria-current="false"></button>
        </div>
    
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://via.placeholder.com/800x600?text=1" class="img-fluid d-block w-100" alt="">
            </div>
    
            <div class="carousel-item">
                <img src="https://via.placeholder.com/800x600?text=2" class="img-fluid d-block w-100" alt="">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Hello there</h5>
                    <p>The slide</p>
                </div>
            </div>
        </div>
    
        <button class="carousel-control-prev" type="button" data-bs-target="#carousel-id-1630232710" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
    
        <button class="carousel-control-next" type="button" data-bs-target="#carousel-id-1630232710" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

Options
-----

Note, that all parameters are optional.

`id`

The id of the main carousel element. If omitted, the ID is generated automatically. It's recommended to define an ID explicitly, in case you require to control the carousel via JavaScript API.

`controls`

Whether to render carousel controls. The default value is true. Note, there must be more than one slide to make controls appear.

`fade`

Whether to add fade effect. The default value is false.

`dark`

Whether to use dark theme. The default value is false;

`touch`

Whether to enable touch swipe on mobile devices. The default value is true.

`interval`

Define change interval in milliseconds. The default value is 5000 (5 seconds)

`pause`

Whether to pause playing on mouse hover. The default value is true.

`keyboard`

Whether carousel should react to keyboard events. The default value is true.

`ride`

Autoplays the carousel after the user manually cycles the first item. If set to 'carousel', autoplays the carousel on load, which is the default value.


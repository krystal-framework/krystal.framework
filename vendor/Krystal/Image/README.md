Image component
===============

This component can help you to build paths and do image processing like cropping, resizing and so on.


# Image processing

Currently it works with GD only. In order to start processing images, you would simply instantiate  `\Krystal\Image\Processor\GD\ImageProcessor` providing one argument, which is a path to an image on the file system or a path to URL.  If it can't open an image, it throws `\RuntimeException`. An example,

    <?php
    
    use Krystal\Image\Processor\GD\ImageProcessor;
    
    $file = '...'
    
    $processor = new ImageProcessor($file);
    $processor->....


# flip()

    \Krystal\Image\Processor\GD\ImageProcessor::flip(int $type)

Flips an image. There are the available constants that you can simply as argument. Here they are:

    \Krystal\Image\Processor\GD\ImageProcessor::IMG_FLIP_HORIZONTAL
    \Krystal\Image\Processor\GD\ImageProcessor::IMG_FLIP_VERTICAL
    \Krystal\Image\Processor\GD\ImageProcessor::IMG_FLIP_BOTH


# watermark()

    \Krystal\Image\Processor\GD\ImageProcessor::(string $watermarkFile, $corner = self::IMG_RIGHT_BOTTOM_CORNER, int $offsetX = 10, int $offsetY = 10)

Adds a watermark to current image. The first argument is path to an image file. The second argument is a constant that defines where a corner should be drawn. It can be one of these:

    \Krystal\Image\Processor\GD\ImageProcessor::IMG_RIGHT_BOTTOM_CORNER
    \Krystal\Image\Processor\GD\ImageProcessor::IMG_RIGHT_TOP
    \Krystal\Image\Processor\GD\ImageProcessor::IMG_LEFT_CORNER
    \Krystal\Image\Processor\GD\ImageProcessor::IMG_LEFT_BOTTOM_CORNER
    \Krystal\Image\Processor\GD\ImageProcessor::CORNER_CENTER

The third and fourth argument are about X and Y offsets. By default both are 10px.


# grayscale()

    \Krystal\Image\Processor\GD\ImageProcessor::grayscale()

Add grayscale filter


# blackwhite()

    \Krystal\Image\Processor\GD\ImageProcessor::blackwhite()


Adds black-white filter, i.e replaces all colors to white and black.


# negative()

    \Krystal\Image\Processor\GD\ImageProcessor::negative()

Applies negative filter

# resize()

    \Krystal\Image\Processor\GD\ImageProcessor::resize(int $x, int $y, bool $proportional = true)

Resizes an image. First argument must be a new height and the second must be a new width. By default it resizes proportionally, but you can change it setting third argument to `false`.

# crop()

    \Krystal\Image\Processor\GD\ImageProcessor::crop(int $width, int $height, int $startX = null, int $startY = null)

Crops an image.


# thumb()

    \Krystal\Image\Processor\GD\ImageProcessor::thumb(int $width, int $height)

Makes a thumbnail (preview) of an image.

# rotate()

    \Krystal\Image\Processor\GD\ImageProcessor::rotate(int $degrees)

Rotates an image. Takes only one argument which is a degree.


# Finishing

Once you finish manipulation, you would want to save an image or maybe to render it. First, let's take a look how to save an image. There's a method `save()` to do that:


    \Krystal\Image\Processor\GD\ImageProcessor::save(string $path, int $quality = 75, string $type = null)

The first argument must be a path to the file an image will be written into. A permissions on the file system for writing is a must. The second `$quality` argument is about quality of outputting image, if used if an image's type supports it. And last argument `$type` allows to save an image with another MIME-type.

If you simply want to render an image directly in a browser, you would use `render()` method instead.

    \Krystal\Image\Processor\GD\ImageProcessor::render(int $quality = 75)
	
# Image manager

Dealing with images of different dimensions is a very common task in many web applications.  For example, you would be dealing with it when developing a photo album, or some kind of social network where each user must have an avatar, or some kind of e-commerce system where each product must have an image with different dimensions.

Krystal provides a dedicated service to deal with that.

    \Krystal\Image\Tool\ImageManager

To start using it, you have to tweak it first. This is done when instantiating the class. The constructor requires the following arguments:

    $path, $rootDir, $rootUrl, array $plugins

Where `$path` is a shared path for `$rootDir` and `$rootUrl`. For example, its value might be `/data/uploads/module/album/` in case you have a photo album. The `$rootDir` and `$rootUrl` are self-explanatory variables - they expect paths of root directory and a root URL respectively.  

The `$plugins` is an array of image handlers. Now let's learn about it.

## Plugins

A plugin is just a sub-component that can process an image. Currently there are 2 available plugins.

### Thumb

A thumb plugin can make thumbs of uploaded images on the fly. You can make an image with many dimensions at once. The plugin has 2 options

`quality` - defines an output quality (i.e quality for an image to be written on file-system). A value must be in rage between 1 and 100. If not specified, then 75 used as a default value.

`dimensions` - is an array of arrays with desired image dimensions. A nested array, as a first arguments takes desired width, and a height as a second.

#### Example

    <?php
    
    use Krystal\Image\Tool\ImageManager;
    
    $path = '/data/uploads/module/album/';
    $rootDir = $_SERVER['DOCUMENT_ROOT'];
    $rootUrl = '/';
    
    // Instantiate and configure the manager
    $im = new ImageManager($path, $rootDir, $rootUrl, array(
       'thumb' => array(
         'dimensions' => array(
           // First key is a width, second one is a height
           array(150, 150),
           array(200, 200)  
          )
        )
    ));

The manager is prepared now. If we call it from some controller's action like this:

    // Assuming that $im has been prepared
    $im->upload('1', $this->request->getFiles())

It will upload an image to `/data/uploads/module/album/1` on the file system with these nested folders:

`150x150`
`200x200`

where each folder will contain a resized (processed via thumbnail) copy of an image.  We used `$upload()` method to upload an image. As a first argument it accepts a unique id, and as a second it accepts a collection returned by `$this->request->getFiles()`


### Original

This plugins uploads an original image to the file system. Optionally it can lower its quality. It's called `original` and accepts an array with the following pair:

`prefix` - a folder name inside provided `$path` to be generated when uploading an image.

`quality` - optionally can be overridden with another quality. The range of quality must be between 1 and 100


#### Example

    <?php
    
    use Krystal\Image\Tool\ImageManager;
    
    $path = '/data/uploads/module/album/';
    $rootDir = $_SERVER['DOCUMENT_ROOT'];
    $rootUrl = '/';
    
    // Instantiate and configure the manager
    $im = new ImageManager($path, $rootDir, $rootUrl, array(
       'thumb' => array(
          // ...
        ),
        'original' => array(
           'prefix' => 'original',
           'quality' => 40
         )
     ));

It can work along with the `thumb` plugin. So when doing an uploading it will upload an image into a folder named `original`.


## Available methods

So far, we've learned how to instantiate and tweak the image manager. Now it's time to learn about its available methods.

### upload(\$id, array \$files)

Uploads a collection to the target unique id. Usually the id is the value of database's last insert id, and the `$files` collection is a returned value of `$this->request->getFiles()`


### delete(\$id, $image = null)

Removes either a whole directory by its associated id, or a single image file (including all its dimensions) from within `$id` folder, if `$image`'s value isn't `null`

### getImageBag()

This method returns a special service that can build paths to images. Before it can be used, it need to be configured - you have to defined a unique id and a file name by calling `setId()` and `setCover()`. For example:

    // Configure the bag
    $imageBag = $im->getImageBag();
    $imageBag->setId('1');
    $imageBag->setCover('foo.jpg');

Once you've done, you can use it to build paths by calling `getUrl()` providing a dimension as an argument. For example:

    echo $imageBag->getUrl('200x200');

The output will be:

    /data/uploads/module/album/1/200x200/foo.jpg


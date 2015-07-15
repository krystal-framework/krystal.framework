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

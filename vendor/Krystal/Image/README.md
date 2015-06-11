Image component
===============

This component allows you to easily handle images.

Imagine if you need to upload one image, make its thumbnails (with many dimensions) and save them. 
Say you have an image called "foo.jpg" and you wait it with:

 - image
  - 100x100
    - foo.jpg
  - 300x300
      - foo.jpg

This might be painfully, you may end up writing whole logic in controllers.

//-----------------------------------------

TODO List:

- Make a warning that Original plugin uploader should be last, or handle it somehow automatically
- rewrite array(100, 100) to array('width' => 100, 'height' => 100)

Planned in next versions:

 * Add ability to add watermarks on Tool\Uploader\OriginalSize
 * Add support for writing text on images
 * Ability to define default images, when requested one doesn't exist on the file-system (in `LocationBuilder`)
 * Batch tools (resizers, watermark adders e.t.c)
 * Need helpers to deal with dimensions as constants
 * Add & implement BMP support
 * Add vintage, sepia
 * Add borders wrappers and rounders support
 * Add shadows support
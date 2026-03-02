Image manager
========

Handling images of different sizes is a frequent requirement in web applications — avatars, product galleries, photo albums, blog thumbnails, etc. Krystal provides a clean and flexible **ImageManager** service to simplify this task.

## Basic concept

ImageManager works with a dedicated upload path and applies processing rules (plugins) during upload. It automatically creates versioned subfolders for each processed variant (thumbnails, optimized originals, etc.).

**Constructor parameters**

    <?php
    
    use Krystal\Image\Tool\ImageManager;
    
    $manager = new ImageManager(
        string $path,           // relative path inside rootDir (e.g. '/data/uploads/album/')
        string $rootDir,        // filesystem root (usually $_SERVER['DOCUMENT_ROOT'])
        string $rootUrl,        // public URL base (usually '/')
        array  $plugins = []    // array of plugin configurations
    )

Example common values:

    $path     = '/data/uploads/album/';
    $rootDir  = $_SERVER['DOCUMENT_ROOT'];
    $rootUrl  = '/';

## Plugins

A plugin is just a sub-component that can process an image. Currently there are 2 available plugins.

### Thumb

A thumb plugin can make thumbs of uploaded images on the fly. You can make an image with many dimensions at once. The plugin has 2 options

`quality` - defines an output quality (i.e quality for an image to be written on file-system). A value must be in rage between 1 and 100. If not specified, then 75 used as a default value.

`dimensions` - is an array of arrays with desired image dimensions. A nested array, as a first arguments takes desired width, and a height as a second.

**Example**

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


**Example**

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

### upload($id, array $files)

Uploads a collection to the target unique id. Usually the id is the value of database's last insert id, and the `$files` collection is a returned value of `$this->request->getFiles()`


### delete($id, $image = null)

Removes either a whole directory by its associated id, or a single image file (including all its dimensions) from within `$id` folder, if `$image`'s value isn't `null`

### getImageBag()

This method returns a special service that can build paths to images. Before it can be used, it need to be configured - you have to defined a unique id and a file name by calling `setId()` and `setCover()`. For example:

Krystal provides a clean and flexible **ImageManager** service to simplify this task:

    // Configure the bag
    $imageBag = $im->getImageBag();
    $imageBag->setId('1');
    $imageBag->setCover('foo.jpg');

Once you've done, you can use it to build paths by calling `getUrl()` providing a dimension as an argument. For example:

    echo $imageBag->getUrl('200x200');

The output will be:

    /data/uploads/module/album/1/200x200/foo.jpg



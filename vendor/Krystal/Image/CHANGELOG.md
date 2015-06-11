CHANGELOG
=========

1.1
---

 * Wrote separated factories for uploaders
 * When max_width and max_height are 0 in `OriginalSize Uploader`, then maximal dimensions aren't taken into account
 * Moved processor's constants into its contract-interface
 * Added base abstract class for factories called "AbstractImageManagerFactory". 
   Now instantiation can be easily abstracted when instantiation logic might be "too" dynamic
 
 * Added ability to define maximal dimensions for `OriginalSize` image uploader
 * Added ability to change image quality in `ImageManager`'s configuration
 * Moved all tools into a dedicated folder `Tool`
 * Added watermarks and flips support in `ImageProcessor`

1.0
---

 * First public version
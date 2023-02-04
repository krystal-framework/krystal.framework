CHANGELOG
=========

1.3
---

 * Added `FileManager::filesize()` to count file size and returns its value in human readable format
 * Added `FileType` class with helper methods
 * Added `FileManager::humanSize()` to convert bytes into human readable sizes
 * In `getDirTree()` forced to ignore dots when making a tree
 * Added `createDir()` to create empty directory
 * Added `hasExtension()` in `Krystal\Filesystem\FileManager`
 * Changed all methods in `FileManager` to static
 * Fixed `FileManager::rmdir()` non-ability to remove hidden files

1.0
---

 * First public version
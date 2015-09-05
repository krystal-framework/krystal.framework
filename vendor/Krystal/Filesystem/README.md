FileSystem component
==================

This component provides a service utility to working with the file system.

    Krystal\Filesystem\FileManager

It has the following methods:

## getBaseName($file)

Extracts and returns a base name from a path. For example, if a target path is `/var/home/config.var` then the resulting base name will be `config.var`

## getExtension($file) 

Extracts and returns an extension of a path. For example, if a path is `/var/home/config.var`, then the resulting extension will be `var`

## getDirName($file)

Extracts and returns a base name from a path.

## getFileName($file)

Returns a file name from a path


## getMimeType($file)

Extracts and returns a Mime-Type from a file.

## getDirTree($dir, $self = false)

Builds and returns a directory tree as an array. The first `$dir` argument is a path to the desired directory, and the seconds `$self` tells whether to include target path (i.e the value of `$dir`) to the resulting array. If invalid directory path supplied, then `RuntimeException` will be thrown.


## getDirSizeCount($dir)

Counts the size of a directory in bytes. If invalid directory path supplied, then `RuntimeException` will be thrown.

## cleanDir($dir)

Removes everything inside a directory. If invalid directory path supplied, then `RuntimeException` will be thrown.

## chmod($file, $chmod)

Recursively applies native PHP's `chmod()` function to a given directory.  The first `$file` argument must be path either to a directory or to a file. And the second `$chmod` defines a mode to be applied.

## rmdir($dir)

Removes a directory, even if it's not empty. The first argument `$dir` is a path to a directory to be removed.

## rmfile($file)

Safely removes a file. That means it would remove a file assigning `777` mode to it before removing it. In case supplied file doesn't exist, it'd throw `RuntimeException`.

## copy($src, $dst)

Copies a directory to the destination path. The first argument `$src` is a path to a target directory, and the second `$dst` is a path to the destination directory. If `$src` is invalid directory path, then `RuntimeException` will be thrown.

## move($from, $to)

Just like as the previous `copy()` method, copies a directory to the destination path, and in addition removes the target directory.

## isFileEmpty($file)

Checks whether target file is empty. Returns boolean value. If invalid file path supplied, then `RuntimeException` will be thrown.

## getFirstLevelDirs($dir)

Returns a directory list (array) inside a target directory. First level means, that it's not aware of recursion, so in case some of resulting directory has a nested one, it will be ignored. In case it can't open a directory, then `UnexpectedValueException` will be thrown.


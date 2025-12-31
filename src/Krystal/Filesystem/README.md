Working with file-system
====================

`FileManager` is a utility class that provides a wide range of common filesystem operations — removing files, traversing directories, copying, cleaning, and more — all with built-in error handling.

To start using it, import the class at the top of your script:

    <?php
    
    use Krystal\Filesystem\FileManager;
    
    ?>

### Remove a file

Deletes a file.  
Throws `RuntimeException` if the given path is not a valid file.

**Usage:**

    FileManager::rmfile('/path/to/file.txt');

### Get directory tree

Returns a flat array of all paths within a directory.  
Throws `RuntimeException` if the path is not a valid directory.

**Usage:**

    $files = FileManager::getDirTree('/path/to/dir', true);

### Get total directory size
Calculates the total size in bytes of all files in a directory (including subdirectories).  

Throws `RuntimeException` if the path is invalid.

Usage:

    $totalBytes = FileManager::getDirSizeCount('/var/www/html/uploads');

### Create a directory
Creates a directory (including parents) if it doesn’t exist. Returns `false` if already present.

    FileManager::createDir('/var/www/html/cache');


### Clean a directory

Removes all contents from a directory but keeps the directory itself.

    FileManager::cleanDir('/var/www/html/tmp');

### Change permissions recursively

Applies the given permission mode to a file or directory recursively.  
Populates `$ignored` with paths that failed.  
Throws `UnexpectedValueException` if the target is invalid.

    $ignored = [];
    FileManager::chmod('/var/www/html/', 0755, $ignored);

### Remove a directory

Recursively deletes a directory and all its contents.  
Throws `RuntimeException` if the path is invalid.

Usage:

    FileManager::rmdir('/var/www/html/old_folder');

### Copy a directory

Recursively copies the source directory into the destination.  
Throws `RuntimeException` if the source path is invalid.

Usage:

    FileManager::copy('/var/www/html/source', '/backup/html/source_copy');

### Move a directory

Moves a directory.

Usage:

    FileManager::move('/var/www/html/temp', '/var/www/html/archive');

### Check if a file is empty

Checks whether a file is empty.  
Throws `RuntimeException` if the given path is not a valid file.

Usage:

    $isEmpty = FileManager::isFileEmpty('/var/log/debug.log');

### Get first-level directories
Returns an array of directories found directly under the given path.  
Throws `UnexpectedValueException` if the directory cannot be read.

Usage:

    $dirs = FileManager::getFirstLevelDirs('/var/www/html');

### Get MIME type of a file

Determines the MIME type of a given file using PHP’s `finfo` or similar internal methods.   Throws `RuntimeException` if the given path is not a valid file.

    $mimeType = FileManager::getMimeType('/path/to/image.jpg'); // e.g., returns "image/jpeg"

### Check if a file has a specific extension

Verifies whether the given file path ends with the specified extension.  
Throws `RuntimeException` if the given path is not a valid file.

Usage:

    $hasExt = FileManager::hasExtension('/path/to/document.pdf', ['pdf']);


### Convert bytes to a human-readable size

Takes a file size in bytes and converts it to a more readable format (KB, MB, GB, etc.), rounded to two decimal places by default.

Usage:

    echo  FileManager::humanSize(1048576); // Output: "1 MB"

### Get file size

Returns the size of the specified file in a human-readable format (e.g., KB, MB, GB).   Internally uses `humanSize()` to convert bytes.  
Throws `RuntimeException` if the path is not a valid file.

Usage:

    $size = FileManager::filesize('/path/to/file.txt');
    // e.g., returns "1 KB"

### Get file name (without path)

Extracts just the file name from a full file path.

Usage:

    $name = FileManager::getFileName('/var/www/index.php'); // Output: "index"

### Get directory name from a path
Returns the directory portion of a file path.

Usage:

    $dir = FileManager::getDirName('/var/www/index.php'); // Output: "/var/www"

### Get file extension

Retrieves the extension of a given file (without the dot).

Usage:

    $ext = FileManager::getExtension('/var/www/index.php'); // Output: "php"

### Get base name

Returns the base file name including its extension.

Usage:

    $base = FileManager::getBaseName('/var/www/index.php'); // Output: "index.php"

### Replace file extension
Replaces the extension of a given file with a new one.

Usage:

    $newFile = FileManager::replaceExtension('/var/www/index.php', 'html'); // Output: "/var/www/index.html"

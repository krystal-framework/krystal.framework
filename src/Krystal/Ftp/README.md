FTP Component
=============

It provides a wrapper for all built-in FTP functions. Usage is almost the same as if you were using its procedural functions, but instead you'd use an object.

# Usage

The factory that instantiates the service has the following signature:

`\Krystal\Ftp\FtpFactory::build($host, $username = null, $password = null, $timeout = 90, $port = 21, $ssl = false)`

If you provided both username and password, it would try to login right after connecting to the host. And the usage itself is pretty-straightforward: 

    <?php
    
    use Krystal\Ftp\FtpFactory;
    
    // Establish a connection and login
    $ftp = FtpFactory::build('some.ftp.host', 'user', 'password');
    
    // Now start using methods
    $data  = $ftp->getRawList('/');

# Available methods

## getOption()

    \Krystal\Ftp\FtpManager::getOption($option)

Retrieves various runtime behaviours of the current FTP stream.

## mkdir()

    \Krystal\Ftp\FtpManager::mkdir($dirname)

Creates new empty directory on the server.

## getRawList()

    \Krystal\Ftp\FtpManager::getRawList($recursive = true)

Returns a detailed list of files in the given directory.

## sendCmd()

    \Krystal\Ftp\FtpManager::sendCmd($cmd)

Sends a command to the server.

## alloc()

    \Krystal\Ftp\FtpManager::alloc($filesize, &$result = null)

Allocates space for a file to be uploaded.

## cdup()

    \Krystal\Ftp\FtpManager::cdup()

Changes to the parent directory.

## chdir()

    \Krystal\Ftp\FtpManager::chdir($directory)

Changes the current directory to the specified one.

## getSystemType()

    \Krystal\Ftp\FtpManager::getSystemType()

Returns the system type identifier of the remote FTP server.

## chmod()

    \Krystal\Ftp\FtpManager::chmod($filename, $mode = 0777)

Set permissions on a file via FTP.

## delete()

    \Krystal\Ftp\FtpManager::delete($path)

Deletes a file on the FTP server.

## exec()

    \Krystal\Ftp\FtpManager::exec($command)

Requests execution of a command on the FTP server.

## fget()

    \Krystal\Ftp\FtpManager::fget($handle, $remoteFile, $mode, $resumepos = 0)

Downloads a file from the FTP server and saves to an open file.

## fput()

    \Krystal\Ftp\FtpManager::fput($handle, $remoteFile, $mode, $resumepos = 0)

Uploads from an open file to the FTP server.

## get()

    \Krystal\Ftp\FtpManager::get($localFile, $remoteFile, $mode, $resumepos = 0)

Downloads a file from the FTP server.

## mdtm()

    \Krystal\Ftp\FtpManager::mdtm($remoteFile)

Returns the last modified time of the given file.

## rmdir()

    \Krystal\Ftp\FtpManager::rmdir($directory)

Removes a directory.

## nbcontinue()

    \Krystal\Ftp\FtpManager::nbcontinue()

Continues retrieving/sending a file (non-blocking).

## nbfget()

    \Krystal\Ftp\FtpManager::nbfget($handle, $remoteFile, $mode, $resumepos = 0)

Retrieves a file from the FTP server and writes it to an open file (non-blocking).

## nbfput()

    \Krystal\Ftp\FtpManager::nbfput($handle, $remoteFile, $mode, $starpos = 0)

Stores a file from an open file to the FTP server (non-blocking).

## nbget()

    \Krystal\Ftp\FtpManager::nbget($localFile, $remoteFile, $mode, $resumepos = 0)

Retrieves a file from the FTP server and writes it to a local file (non-blocking).

## nbput()

    \Krystal\Ftp\FtpManager::nbput($remoteFile, $localFile, $mode, $starpos = 0)

Stores a file on the FTP server (non-blocking).

## nlist()

    \Krystal\Ftp\FtpManager::nlist($directory)

Returns a list of files in the given directory.

## pasv()

    \Krystal\Ftp\FtpManager::pasv($pasv)

Turns passive mode on or off.

## pwd()

    \Krystal\Ftp\FtpManager::pwd()

Returns the current directory name.

## rename()

    \Krystal\Ftp\FtpManager::rename($oldName, $newName)

Renames a file or a directory on the FTP server.

## site()

    \Krystal\Ftp\FtpManager::site($command)

Sends a SITE command to the server.

## size()

    \Krystal\Ftp\FtpManager::size($remoteFile)

Returns the size of the given file.
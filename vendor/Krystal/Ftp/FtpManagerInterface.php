<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Ftp;

interface FtpManagerInterface
{
	/**
	 * Retrieves various runtime behaviours of the current FTP stream
	 * 
	 * @param integer $option
	 * @return boolean
	 */
	public function getOption($option);

	/**
	 * Creates new empty directory on the server
	 * 
	 * @param string $dirname
 	 * @return boolean Depending on success
	 */
	public function mkdir($dirname);

	/**
	 * Sends a command to the server
	 * 
	 * @param string $cmd
	 * @return array
	 */
	public function sendCmd($cmd);

	/**
	 * Allocates space for a file to be uploaded
	 * 
	 * @param integer $filesize
	 * @param string $result
	 * @return boolean Depending on success
	 */
	public function alloc($filesize, &$result = null);

	/**
	 * Changes to the parent directory
	 * 
	 * @return boolean Depending on success
	 */
	public function cdup();

	/**
	 * Changes the current directory to the specified one
	 * 
	 * @param string $directory
	 * @return boolean Depending on success
	 */
	public function chdir($directory);

	/**
	 * Returns the system type identifier of the remote FTP server
	 * 
	 * @return string
	 */
	public function getSystemType();

	/**
	 * Set permissions on a file via FTP
	 * 
	 * @param string $filename
	 * @param integer $mode
	 * @return boolean
	 */
	public function chmod($filename, $mode = 0777);

	/**
	 * Deletes a file on the FTP server
	 * 
	 * @param string $path
	 * @return boolean
	 */
	public function delete($path);

	/**
	 * Requests execution of a command on the FTP server
	 * 
	 * @param string $command
	 * @return boolean Depending on success
	 */
	public function exec($command);

	/**
	 * Downloads a file from the FTP server and saves to an open file
	 * 
	 * @param resource $handle An open file pointer in which we store the data
	 * @param string $remoteFile The remote file path
	 * @param integer $mode The transfer mode. Must be either \FTP_ASCII or \FTP_BINARY
	 * @param integer $resumepos The position in the remote file to start downloading from
	 * @return boolean
	 */
	public function fget($handle, $remoteFile, $mode, $resumepos = 0);

	/**
	 * Uploads from an open file to the FTP server
	 * 
	 * @param resource $handle An open file pointer on the local file. Reading stops at end of file
	 * @param string $remoteFile The remote file path
	 * @param integer $mode The transfer mode. Must be either FTP_ASCII or FTP_BINARY
	 * @param integer $resumepos The position in the remote file to start uploading to
	 * @return boolean
	 */
	public function fput($handle, $remoteFile, $mode, $resumepos = 0);

	/**
	 * Downloads a file from the FTP server
	 * 
	 * @param string $localFile The local file path (will be overwritten if the file already exists). 
	 * @param string $remoteFile The remote file path. 
	 * @param integer $mode The transfer mode. Must be either FTP_ASCII or FTP_BINARY
	 * @param integer $resumepos
	 * @return boolean Depending on success
	 */
	public function get($localFile, $remoteFile, $mode, $resumepos = 0);

	/**
	 * Returns the last modified time of the given file
	 * 
	 * @param string $remoteFile The file from which to extract the last modification time. 
	 * @return integer The last modified time as a Unix timestamp on success, or -1 on error. 
	 */
	public function mdtm($remoteFile);

	/**
	 * Removes a directory
	 * 
	 * @param string $directory
	 * @return boolean Depending on success
	 */
	public function rmdir($directory);

	/**
	 * Continues retrieving/sending a file (non-blocking)
	 * 
	 * @return integer \FTP_FAILED or \FTP_FINISHED or \FTP_MOREDATA
	 */
	public function nbcontinue();

	/**
	 * Retrieves a file from the FTP server and writes it to an open file (non-blocking)
	 * 
	 * @param resource $handle An open file pointer in which we store the data.
	 * @param string $remoteFile The remote file path
	 * @param integer $mode The transfer mode. Must be either \FTP_ASCII or \FTP_BINARY
	 * @param integer $resumepos The position in the remote file to start downloading from
	 * @return integer \FTP_FAILED or \FTP_FINISHED or \FTP_MOREDATA
	 */
	public function nbfget($handle, $remoteFile, $mode, $resumepos = 0);

	/**
	 * Stores a file from an open file to the FTP server (non-blocking)
	 * 
	 * @param resource $handle An open file pointer on the local file. Reading stops at end of file
	 * @param string $remoteFile The remote file path
	 * @param integer $mode The transfer mode. Must be either \FTP_ASCII or \FTP_BINARY
	 * @param integer $starpos The position in the remote file to start uploading to
	 * @return integer \FTP_FAILED or \FTP_FINISHED or \FTP_MOREDATA
	 */
	public function nbfput($handle, $remoteFile, $mode, $starpos = 0);

	/**
	 * Retrieves a file from the FTP server and writes it to a local file (non-blocking)
	 * 
	 * @param string $localFile The local file path (will be overwritten if the file already exists)
	 * @param string $remoteFile The remote file path
	 * @param integer $mode The transfer mode. Must be either \FTP_ASCII or \FTP_BINARY
	 * @param integer $resumepos The position in the remote file to start downloading from
	 * @return integer \FTP_FAILED or \FTP_FINISHED or \FTP_MOREDATA
	 */
	public function nbget($localFile, $remoteFile, $mode, $resumepos = 0);

	/**
	 * Stores a file on the FTP server (non-blocking)
	 * 
	 * @param string $remoteFile The link identifier of the FTP connection
	 * @param string $localFile The remote file path
	 * @param integer $mode The transfer mode. Must be either \FTP_ASCII or \FTP_BINARY
	 * @param integer $starpos The position in the remote file to start uploading to
	 * @return integer \FTP_FAILED or \FTP_FINISHED or \FTP_MOREDATA
	 */
	public function nbput($remoteFile, $localFile, $mode, $starpos = 0);

	/**
	 * Returns a list of files in the given directory
	 * 
	 * @param string $directory
	 * @return array
	 */
	public function nlist($directory);

	/**
	 * Turns passive mode on or off
	 * 
	 * @param boolean $pasv
	 * @return boolean Depending on success
	 */
	public function pasv($pasv);

	/**
	 * Returns the current directory name
	 * 
	 * @return string
	 */
	public function pwd();

	/**
	 * Returns a detailed list of files in the given directory
	 * 
	 * @param string $directory The directory path. May include arguments for the LIST command. 
	 * @param boolean $recursive If set to TRUE, the issued command will be LIST -R.
	 * @return array Returns an array where each element corresponds to one line of text. 
	 */
	public function getRawList($directory, $recursive = true);

	/**
	 * Renames a file or a directory on the FTP server
	 * 
	 * @param string $oldName
	 * @param string $newName
	 * @return boolean Depending on success
	 */
	public function rename($oldName, $newName);

	/**
	 * Sends a SITE command to the server
	 * 
	 * @param string $command
	 * @return boolean Depending on success
	 */
	public function site($command);

	/**
	 * Returns the size of the given file
	 * 
	 * @param string $remoteFile
	 * @return integer
	 */
	public function size($remoteFile);
}

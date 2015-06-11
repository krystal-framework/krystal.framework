<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Ftp;

interface FtpManagerInterface
{
	/**
	 * Makes empty directory
	 * 
	 * @param string $dir_name
	 * @return boolean Depending on success
	 */
	public function mkdir($dir_name);

	/**
	 * Sends a command to the server
	 * 
	 * @param string $cmd
	 * @return boolean
	 */
	public function sendCmd($cmd);

	/**
	 * Changes mode
	 * 
	 * @param string $filename
	 * @param integer $mode
	 */
	public function chmod($filename, $mode = 0777);

	/**
	 * Returns raw list of 
	 * 
	 * @param boolean $recursive Whether it should be recursive or not
	 * @return array
	 */	
	public function getRawList($recursive = true);

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
	 * Deletes a file on the FTP server
	 * 
	 * @param string $path
	 * @return boolean Depending on success
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
	 * @param string $remote_file The remote file path
	 * @param integer $mode The transfer mode. Must be either FTP_ASCII or FTP_BINARY
	 * @param integer $resumepos The position in the remote file to start downloading from
	 * @return boolean Depending on success
	 */
	public function fget($handle, $remote_file, $mode, $resumepos = 0);

	/**
	 * Uploads from an open file to the FTP server
	 * 
	 * @param resource $handle An open file pointer on the local file. Reading stops at end of file
	 * @param string $remote_file The remote file path
	 * @param integer $mode The transfer mode. Must be either FTP_ASCII or FTP_BINARY
	 * @param integer $resumepos The position in the remote file to start uploading to
	 * @return boolean Depending on success
	 */
	public function fput($handle, $remote_file, $mode, $resumepos = 0);

	/**
	 * Downloads a file from the FTP server
	 * 
	 * @param string $local_file The local file path (will be overwritten if the file already exists). 
	 * @param string $remote_file The remote file path. 
	 * @param integer $mode The transfer mode. Must be either FTP_ASCII or FTP_BINARY
	 * @param integer $resumepos 
	 * @return boolean Depending on success
	 */
	public function get($local_file, $remote_file, $mode, $resumepos = 0);

	/**
	 * Returns the last modified time of the given file
	 * 
	 * @param string $remote_file The file from which to extract the last modification time. 
	 * @return integer The last modified time as a Unix timestamp on success, or -1 on error. 
	 */
	public function mdtm($remote_file);

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
	 * @return integer FTP_FAILED or FTP_FINISHED or FTP_MOREDATA. 
	 */
	public function nbcontinue();

	/**
	 * Retrieves a file from the FTP server and writes it to an open file (non-blocking)
	 * 
	 * @param resource $handle An open file pointer in which we store the data.
	 * @param string $remote_file The remote file path
	 * @param integer $mode The transfer mode. Must be either FTP_ASCII or FTP_BINARY
	 * @param integer $resumepos The position in the remote file to start downloading from
	 * @return integer FTP_FAILED or FTP_FINISHED or FTP_MOREDATA. 
	 */
	public function nbfget($handle, $remote_file, $mode, $resumepos = 0);

	/**
	 * Stores a file from an open file to the FTP server (non-blocking)
	 * 
	 * @param resource $handle An open file pointer on the local file. Reading stops at end of file
	 * @param string $remote_file The remote file path
	 * @param integer $mode The transfer mode. Must be either FTP_ASCII or FTP_BINARY
	 * @param integer $starpos The position in the remote file to start uploading to
	 * @return integer FTP_FAILED or FTP_FINISHED or FTP_MOREDATA
	 */
	public function nbfput($handle, $remote_file, $mode, $starpos = 0);

	/**
	 * Retrieves a file from the FTP server and writes it to a local file (non-blocking)
	 * 
	 * @param string $local_file The local file path (will be overwritten if the file already exists)
	 * @param string $remote_file The remote file path
	 * @param integer $mode The transfer mode. Must be either FTP_ASCII or FTP_BINARY
	 * @param integer $resumepos The position in the remote file to start downloading from
	 * @return integer FTP_FAILED or FTP_FINISHED or FTP_MOREDATA
	 */
	public function nbget($local_file, $remote_file, $mode, $resumepos = 0);

	/**
	 * Stores a file on the FTP server (non-blocking)
	 * 
	 * @param string $remote_file The link identifier of the FTP connection
	 * @param string $local_file The remote file path
	 * @param integer $mode The transfer mode. Must be either FTP_ASCII or FTP_BINARY
	 * @param integer $starpos The position in the remote file to start uploading to
	 * @return integer FTP_FAILED or FTP_FINISHED or FTP_MOREDATA
	 */
	public function nbput($remote_file, $local_file, $mode, $starpos = 0);

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
	 * @param string $old_name
	 * @param string $new_name
	 * @return boolean Depending on success
	 */
	public function rename($old_name, $new_name);
	
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
	 * @param string $remote_file
	 * @return integer
	 */
	public function size($remote_file);	
}

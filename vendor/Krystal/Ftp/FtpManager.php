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

final class FtpManager implements FtpManagerInterface
{
	/**
	 * FTP stream provider
	 * 
	 * @var \Krystal\Ftp\ConnectorInterface
	 */
	private $link;

	/**
	 * State initialization
	 * 
	 * @param \Krystal\Ftp\ConnectorInterface $link
	 * @return void
	 */
	public function __construct(ConnectorInterface $link)
	{
		$this->link = $link;
	}

	/**
	 * Frees the memory
	 * 
	 * @return void
	 */
	public function __destruct()
	{
		$this->link->disconnect();
	}

	/**
	 * Retrieves various runtime behaviours of the current FTP stream
	 * 
	 * @param integer $option
	 * @return boolean
	 */
	public function getOption($option)
	{
		return ftp_get_option($this->stream, $option);
	}

	/**
	 * Creates new empty directory on the server
	 * 
	 * @param string $dirname
 	 * @return boolean Depending on success
	 */
	public function mkdir($dirname)
	{
		return ftp_mkdir($this->link->getStream(), $dirname) !== false;
	}

	/**
	 * Returns a detailed list of files in the given directory
	 * 
	 * @param string $directory The directory path. May include arguments for the LIST command. 
	 * @param boolean $recursive If set to TRUE, the issued command will be LIST -R.
	 * @return array Returns an array where each element corresponds to one line of text. 
	 */
	public function getRawList($directory, $recursive = true)
	{
		return ftp_rawlist($this->link->getStream(), $directory, $recursive);
	}

	/**
	 * Sends a command to the server
	 * 
	 * @param string $cmd
	 * @return array
	 */
	public function sendCmd($cmd)
	{
		return ftp_raw($this->link->getStream(), $cmd);
	}

	/**
	 * Allocates space for a file to be uploaded
	 * 
	 * @param integer $filesize
	 * @param string $result
	 * @return boolean Depending on success
	 */
	public function alloc($filesize, &$result = null)
	{
		return ftp_alloc($this->link->getStream(), $filesize, $result);
	}

	/**
	 * Changes to the parent directory
	 * 
	 * @return boolean Depending on success
	 */
	public function cdup()
	{
		return ftp_cdup($this->link->getStream());
	}

	/**
	 * Changes the current directory to the specified one
	 * 
	 * @param string $directory
	 * @return boolean Depending on success
	 */
	public function chdir($directory)
	{
		return ftp_chdir($this->link->getStream(), $directory);
	}

	/**
	 * Returns the system type identifier of the remote FTP server
	 * 
	 * @return string
	 */
	public function getSystemType()
	{
		return ftp_systype($this->link->getStream());
	}

	/**
	 * Set permissions on a file via FTP
	 * 
	 * @param string $filename
	 * @param integer $mode
	 * @return boolean
	 */
	public function chmod($filename, $mode = 0777)
	{
		return ftp_chmod($this->link->getStream(), $mode, $filename);
	}

	/**
	 * Deletes a file on the FTP server
	 * 
	 * @param string $path
	 * @return boolean
	 */
	public function delete($path)
	{
		return ftp_delete($this->link->getStream(), $path);
	}

	/**
	 * Requests execution of a command on the FTP server
	 * 
	 * @param string $command
	 * @return boolean Depending on success
	 */
	public function exec($command)
	{
		return ftp_exec($this->link->getStream(), $command);
	}

	/**
	 * Downloads a file from the FTP server and saves to an open file
	 * 
	 * @param resource $handle An open file pointer in which we store the data
	 * @param string $remoteFile The remote file path
	 * @param integer $mode The transfer mode. Must be either \FTP_ASCII or \FTP_BINARY
	 * @param integer $resumepos The position in the remote file to start downloading from
	 * @return boolean
	 */
	public function fget($handle, $remoteFile, $mode, $resumepos = 0)
	{
		return ftp_fget($this->link->getStream(), $handle, $remoteFile, $mode, $resumepos);
	}

	/**
	 * Uploads from an open file to the FTP server
	 * 
	 * @param resource $handle An open file pointer on the local file. Reading stops at end of file
	 * @param string $remoteFile The remote file path
	 * @param integer $mode The transfer mode. Must be either FTP_ASCII or FTP_BINARY
	 * @param integer $resumepos The position in the remote file to start uploading to
	 * @return boolean
	 */
	public function fput($handle, $remoteFile, $mode, $resumepos = 0)
	{
		return ftp_fput($this->link->getStream(), $remoteFile, $handle, $mode, $resumepos);
	}

	/**
	 * Downloads a file from the FTP server
	 * 
	 * @param string $localFile The local file path (will be overwritten if the file already exists). 
	 * @param string $remoteFile The remote file path. 
	 * @param integer $mode The transfer mode. Must be either FTP_ASCII or FTP_BINARY
	 * @param integer $resumepos
	 * @return boolean Depending on success
	 */
	public function get($localFile, $remoteFile, $mode, $resumepos = 0)
	{
		return ftp_get($this->link->getStream(), $localFile, $remoteFile, $mode, $resumepos);
	}

	/**
	 * Returns the last modified time of the given file
	 * 
	 * @param string $remoteFile The file from which to extract the last modification time. 
	 * @return integer The last modified time as a Unix timestamp on success, or -1 on error. 
	 */
	public function mdtm($remoteFile)
	{
		return ftp_mdtm($this->link->getStream(), $remoteFile);
	}

	/**
	 * Removes a directory
	 * 
	 * @param string $directory
	 * @return boolean Depending on success
	 */
	public function rmdir($directory)
	{
		return ftp_rmdir($this->link->getStream(), $directory);
	}

	/**
	 * Continues retrieving/sending a file (non-blocking)
	 * 
	 * @return integer \FTP_FAILED or \FTP_FINISHED or \FTP_MOREDATA
	 */
	public function nbcontinue()
	{
		return ftp_nb_continue($this->link->getStream());
	}

	/**
	 * Retrieves a file from the FTP server and writes it to an open file (non-blocking)
	 * 
	 * @param resource $handle An open file pointer in which we store the data.
	 * @param string $remoteFile The remote file path
	 * @param integer $mode The transfer mode. Must be either \FTP_ASCII or \FTP_BINARY
	 * @param integer $resumepos The position in the remote file to start downloading from
	 * @return integer \FTP_FAILED or \FTP_FINISHED or \FTP_MOREDATA
	 */
	public function nbfget($handle, $remoteFile, $mode, $resumepos = 0)
	{
		return ftp_nb_fget($this->link->getStream(), $hamdle, $remoteFile, $mode, $resumepos);
	}

	/**
	 * Stores a file from an open file to the FTP server (non-blocking)
	 * 
	 * @param resource $handle An open file pointer on the local file. Reading stops at end of file
	 * @param string $remoteFile The remote file path
	 * @param integer $mode The transfer mode. Must be either \FTP_ASCII or \FTP_BINARY
	 * @param integer $starpos The position in the remote file to start uploading to
	 * @return integer \FTP_FAILED or \FTP_FINISHED or \FTP_MOREDATA
	 */
	public function nbfput($handle, $remoteFile, $mode, $starpos = 0)
	{
		return ftp_nb_fput($this->link->getStream(), $remoteFile, $handle, $mode, $starpos);
	}

	/**
	 * Retrieves a file from the FTP server and writes it to a local file (non-blocking)
	 * 
	 * @param string $localFile The local file path (will be overwritten if the file already exists)
	 * @param string $remoteFile The remote file path
	 * @param integer $mode The transfer mode. Must be either \FTP_ASCII or \FTP_BINARY
	 * @param integer $resumepos The position in the remote file to start downloading from
	 * @return integer \FTP_FAILED or \FTP_FINISHED or \FTP_MOREDATA
	 */
	public function nbget($localFile, $remoteFile, $mode, $resumepos = 0)
	{
		return ftp_nb_get($this->link->getStream(), $localFile, $remoteFile, $mode, $resumepos);
	}

	/**
	 * Stores a file on the FTP server (non-blocking)
	 * 
	 * @param string $remoteFile The link identifier of the FTP connection
	 * @param string $localFile The remote file path
	 * @param integer $mode The transfer mode. Must be either \FTP_ASCII or \FTP_BINARY
	 * @param integer $starpos The position in the remote file to start uploading to
	 * @return integer \FTP_FAILED or \FTP_FINISHED or \FTP_MOREDATA
	 */
	public function nbput($remoteFile, $localFile, $mode, $starpos = 0)
	{
		return ftp_nb_put($this->link->getStream(), $remoteFile, $localFile, $mode, $starpos);
	}

	/**
	 * Returns a list of files in the given directory
	 * 
	 * @param string $directory
	 * @return array
	 */
	public function nlist($directory)
	{
		return ftp_nlist($this->link->getStream(), $directory);
	}

	/**
	 * Turns passive mode on or off
	 * 
	 * @param boolean $pasv
	 * @return boolean Depending on success
	 */
	public function pasv($pasv)
	{
		return ftp_pasv($this->link->getStream(), $pasv);
	}

	/**
	 * Returns the current directory name
	 * 
	 * @return string
	 */
	public function pwd()
	{
		return ftp_pwd($this->link->getStream());
	}

	/**
	 * Renames a file or a directory on the FTP server
	 * 
	 * @param string $oldName
	 * @param string $newName
	 * @return boolean Depending on success
	 */
	public function rename($oldName, $newName)
	{
		return ftp_rename($this->link->getStream(), $oldName, $newName);
	}

	/**
	 * Sends a SITE command to the server
	 * 
	 * @param string $command
	 * @return boolean Depending on success
	 */
	public function site($command)
	{
		return ftp_site($this->link->getStream(), $command);
	}

	/**
	 * Returns the size of the given file
	 * 
	 * @param string $remoteFile
	 * @return integer
	 */
	public function size($remoteFile)
	{
		return ftp_size($this->link->getStream(), $remoteFile);
	}
}

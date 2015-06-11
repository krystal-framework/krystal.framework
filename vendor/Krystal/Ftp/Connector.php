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

final class Connector implements ConnectorInterface
{
	/**
	 * Connection stream itself
	 * 
	 * @var resource
	 */
	private $stream;

	/**
	 * Connections options
	 * 
	 * @var array
	 */
	private $options = array();
   
	/**
	 * Whether user logged in using his username and password
	 * 
	 * @var boolean
	 */
	private $loggedIn = false;

	/**
	 * Error messages regarding connection
	 * 
	 * @var array
	 */
	private $errors = array();

	/**
	 * State initialization
	 * 
	 * @param array $options
	 * @return void
	 */
	public function __construct(array $options)
	{
		$this->options = $options;

		if (isset($options['timeout']) && is_numeric($options['timeout'])){
			$this->options['timeout'] = $options['timeout'];
		} else {
			$this->options['timeout'] = 90;
		}

		if (isset($options['port']) && is_numeric($options['port'])){
			$this->options['port'] = $options['port'];
		} else {
			$this->options['port'] = 21;
		}
	}

	/**
	 * Free memory up if possible
	 * 
	 * @return void
	 */
	public function __destruct()
	{
		if (is_resource($this->stream)) {
			$this->disconnect();
		}
	}

	/**
	 * Tells whether user connected or not
	 * 
	 * @return boolean
	 */
	public function isConnected()
	{
		return is_resource($this->stream);
	}

	/**
	 * Check whether user successfully logged in
	 * 
	 * @return boolean
	 */
	public function isLoggedIn()
	{
		return $this->loggedIn;
	}

	/**
	 * Returns a stream
	 * 
	 * @return resource
	 */
	public function getStream()
	{
		return $this->stream;
	}

	/**
	 * Return defined options
	 * 
	 * @return array
	 */
	public function getOptions()
	{
		return $this->options;
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
	 * Return last error messages
	 * 
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * Connects to the server
	 * 
	 * @param boolean $ssl Whether to use SSL
	 * @return boolean Depending on success
	 */
	public function connect($ssl = false)
	{
		$arguments = array($this->options['host'], $this->options['port'], $this->options['timeout'])

		if ($ssl !== false) {
			$result = call_user_func_array('ftp_ssl_connect', $arguments);
		} else {
			$result = call_user_func_array('ftp_connect', $arguments);
		}

		if (!$result) {
			return false;
		} else {
			$this->stream = $result;
			return true;
		}
	}

	/**
	 * Disconnects from FTP server
	 * 
	 * @return boolean Depending on success
	 */
	public function disconnect()
	{
		return ftp_close($this->stream);
	}

	/**
	 * Logins using username and password
	 * 
	 * @throws \RuntimeException if missing either username or password
	 * @return boolean Depending on success
	 */
	public function login()
	{
		if (isset($this->options['username'], $this->options['password'])) {
			// We don't want E_WARNING
			return @ftp_login($this->stream, $this->options['username'], $this->options['password']);
		} else {
			throw new RuntimeException('You did not provide username or password');
		}
	}
}
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

final class Connector implements ConnectorInterface
{
	/**
	 * Connection stream itself
	 * 
	 * @var resource
	 */
	private $stream;

	/**
	 * Whether user logged in using his username and password
	 * 
	 * @var boolean
	 */
	private $loggedIn = false;

	/**
	 * Connection timeout
	 * 
	 * @var integer
	 */
	private $timeout;

	/**
	 * Connection port
	 * 
	 * @var integer
	 */
	private $port;
	
	/**
	 * Connection host address
	 * 
	 * @var string
	 */
	private $host;
	
	/**
	 * Whether to connect via SSL
	 * 
	 * @var boolean
	 */
	private $ssl;

	/**
	 * State initialization
	 * 
	 * @param string $host
	 * @param integer $timeout
	 * @param integer $port
	 * @param boolean $ssl
	 * @return void
	 */
	public function __construct($host, $timeout = 90, $port = 21, $ssl = false)
	{
		$this->host = $host;
		$this->timeout = $timeout;
		$this->port = $port;
		$this->ssl = $ssl;
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
	public function &getStream()
	{
		return $this->stream;
	}

	/**
	 * Connects to the server
	 * 
	 * @return boolean Depending on success
	 */
	public function connect()
	{
		if ($this->ssl !== false) {
			$function = 'ftp_ssl_connect';
		} else {
			$function = 'ftp_connect';
		}

		$result = call_user_func_array($function, array($this->host, $this->port, $this->timeout));

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
		if (is_resource($this->stream)) {
			return ftp_close($this->stream);
		} else {
			return false;
		}
	}

	/**
	 * Logins using username and password
	 * 
	 * @param string $username
	 * @param string $password
	 * @return boolean Depending on success
	 */
	public function login($username, $password)
	{
		// @ - intentionally, because false is enough
		if (@ftp_login($this->stream, $username, $password)) {
			$this->loggedIn = true;
			return true;

		} else {
			return false;
		}
	}
}

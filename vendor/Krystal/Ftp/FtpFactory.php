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

use RuntimeException;
use LogicException;

final class FtpFactory
{
	/**
	 * Builds FTP Manager
	 * 
	 * @param string $host
	 * @param string $username
	 * @param string $password
	 * @param integer $timeout
	 * @param integer $port
	 * @param boolean $ssl
	 * @throws \RuntimeException If cannot connect to the remote host
	 * @throws \LogicException If Invalid combination of username and password provided
	 * @return \Krystal\Ftp\FtpManager
	 */
	public static function build($host, $username = null, $password = null, $timeout = 90, $port = 21, $ssl = false)
	{
		$connector = new Connector($host, $timeout, $port, $ssl);

		if (!$connector->connect()) {
			throw new RuntimeException('Cannot connect to remote FTP server');
		}

		if ($username !== null && $password !== null) {
			if (!$connector->login($username, $password)) {
				throw new LogicException('Invalid combination of username and password provided');
			}
		}

		return new FtpManager($connector);
	}
}

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

class FtpFactory
{
	/**
	 * Builds FTP Manager
	 * 
	 * @param array $options
	 * @return \Krystal\Ftp\FtpManager
	 */
	public static function build(array $options)
	{
		$connector = new Connector($options);
		return new FtpManager($connector);
	}
}

<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Cache\Memcached;

use Memcached;

abstract class Connector
{
	/**
	 * Builds Memcached instance
	 * 
	 * @param array $servers
	 * @return \Memcached
	 */
	public static function build(array $servers)
	{
		$memcached = new Memcached();

		foreach ($servers as $server) {
			if (!isset($server['weight'])) {
				$server['weight'] = 0;
			}
		}

		$memcached->addServers($servers);
		return $memcached;
	}
}

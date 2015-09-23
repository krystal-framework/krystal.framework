<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Cache\FileEngine;

use Krystal\Serializer\NativeSerializer;

abstract class FileEngineFactory
{
	/**
	 * Builds cache instance
	 * 
	 * @param string $file The path to the cache file
	 * @param boolean $autoCreate
	 * @return \Krystal\Cache\FileEngine\CacheEngine
	 */
	public static function build($file, $autoCreate = true)
	{
		$storage = new CacheFile(new NativeSerializer(), $file, $autoCreate);

		$cache = new CacheEngine(new ArrayCache(), new ArraySignature(), $storage);
		$cache->initialize();

		return $cache;
	}
}

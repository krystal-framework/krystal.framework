<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) 2015 David Yang <daworld.ny@gmail.com>
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Krystal\Application\Component;

use Krystal\Application\InputInterface;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;
use Krystal\Cache\FileEngine\FileEngineFactory;
use Krystal\Cache\Sql\SqlEngineFactory;
use Krystal\Cache\WinCache;
use Krystal\Cache\APC;
use Krystal\Cache\XCache;

final class Cache implements ComponentInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input)
	{
		// First of all, check cache component is required
		if (isset($config['components']['cache']['engine'])) {

			// Reference as a short-cut
			$component =& $config['components']['cache'];
			$options =& $config['components']['cache']['options'];

			switch ($component['engine']) {
				case 'file' :
					// By default, a file need to be created automatically if it doesn't exist
					$autoCreate = true;

					if (isset($options['auto_create']) && is_bool($options['auto_create'])){
						$autoCreate = $options['auto_create'];
					}

					return FileEngineFactory::build($options['file'], $autoCreate);
				break;
				
				case 'wincache';
					return new WinCache();
				
				case 'apc':
					return new APC();
				
				case 'xcache':
					return new XCache();
				
				case 'sql':
					
					$table = $options['table'];
					
					$db = $container->get('db');
					$connection = $db[$options['connection']];
					
					$pdo = $connection->getPdo();
					
					return SqlEngineFactory::build($pdo, $table);
					
				default : 
					throw new \RuntimeException(sprintf('Invalid engine provided %s', $component['engine']));
			}

		} else {
			// Not defined in configuration
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'cache';
	}
}

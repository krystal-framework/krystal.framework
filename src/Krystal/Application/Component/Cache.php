<?php

/**
 * This file is part of the Krystal Framework
 * 
 * Copyright (c) No Global State Lab
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
use Krystal\Cache\Memcached\MemcachedFactory;
use RuntimeException;

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
                case 'memcached':
                    if (!isset($options['servers']) || !is_array($options['servers'])) {
                        throw new RuntimeException('Memcached servers must be defined in configuration');
                    }

                    return MemcachedFactory::build($options['servers']);

                case 'file' :
                    // By default, a file need to be created automatically if it doesn't exist
                    $autoCreate = true;

                    if (isset($options['auto_create']) && is_bool($options['auto_create'])){
                        $autoCreate = $options['auto_create'];
                    }

                    return FileEngineFactory::build($options['file'], $autoCreate);

                case 'wincache';
                    return new WinCache();

                case 'apc':
                    return new APC();

                case 'xcache':
                    return new XCache();

                case 'sql':
                    if (!isset($options['table']) || !isset($options['connection'])) {
                        throw new RuntimeException('Cache SQL service request connection and table names to run');
                    }

                    $table = $options['table'];

                    $db = $container->get('db');
                    $connection = $db[$options['connection']];

                    $pdo = $connection->getPdo();

                    return SqlEngineFactory::build($pdo, $table);

                default : 
                    throw new RuntimeException(sprintf('Invalid engine provided %s', $component['engine']));
            }

        } else {
            // Not defined in configuration
            return false;
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

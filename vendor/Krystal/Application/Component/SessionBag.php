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

use Krystal\Session\SessionBag as Component;
use Krystal\Session\SessionValidator;
use Krystal\Application\InputInterface;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;
use Krystal\Session\Adapter;
use RunitmeException;
use LogicException;

final class SessionBag implements ComponentInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input)
	{
		// Defaults
		$handler = null;
		$cookieParams = array();

		// Start configuring if possible
		if (isset($config['components']['session'])) {
			// Just a short-cut
			$config = $config['components']['session'];

			// Alter default $cookieParams if needed
			if (isset($config['cookie_params']) && is_array($config['cookie_params'])) {
				$cookieParams = $config['cookie_params'];
			}

			// Check if there's a custom session handler
			if (isset($config['handler'])) {
				switch ($config['handler']) {
					case 'sql':

						// Make sure the database component is available, before processing the rest
						if (!$container->exists('db')) {
							throw new LogicException('Can not use SQL session handler without configured database connection');
						}

						// Grab available database connection
						$db = $container->get('db');

						if (isset($config['options']['connection']) && isset($config['options']['table'])) {
							// Grab connection's name
							$connection = $db[$config['options']['connection']];
							
							// Now alter default handler
							$handler = new Adapter\Sql($connection->getPdo(), $config['options']['table']);

						} else {
							throw new LogicException('No connection or table name defined for session service');
						}

					break;

					default:
						throw new RunitmeException(sprintf('Unsupported session handler supplied "%s"', $config['handler']));
				}
			}
		}

		$cookieBag = $container->get('request')->getCookieBag();
		$sessionBag = new Component($cookieBag, new SessionValidator($input->getServer()), $handler);

		$sessionBag->start($cookieParams);
		return $sessionBag;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'sessionBag';
	}
}

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

use Krystal\Application\View\ViewManager;
use Krystal\Application\View\Resolver\Module as Resolver;
use Krystal\Application\InputInterface;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;

final class View implements ComponentInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input)
	{
		$translator = $container->get('translator');
		$appConfig = $container->get('appConfig');
		$urlBuilder = $container->get('urlBuilder');

		if (isset($config['components']['view']['obfuscate']) && is_bool($config['components']['view']['obfuscate'])) {
			$compress = $config['components']['view']['obfuscate'];
		} else {
			// By default we don't want to compress an output
			$compress = false;
		}

		// Resolver will be injected later
		$viewManager = new ViewManager($translator, $urlBuilder, $compress);

		if (isset($config['components']['view'])) {
			if (isset($config['components']['view']['plugins'])) {
				$viewManager->getPluginBag()->register($config['components']['view']['plugins']);
			}
		}

		// Add flash messenger, so that it's available in templates
		$viewManager->addVariable('flashBag', $container->get('flashBag'));

		return $viewManager;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'view';
	}
}

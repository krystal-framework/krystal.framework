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

use Krystal\Captcha\Standard as Component;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;
use Krystal\Application\InputInterface;
use Krystal\Captcha\Standard\CaptchaFactory;

final class Captcha implements ComponentInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input)
	{
		$sessionBag = $container->get('sessionManager');

		if (isset($config['components']['captcha']['type'])) {

			// By default, no options to override
			$options = array();

			// Alter $options if we have them in user's configuration
			if (isset($config['components']['captcha']['options']) && is_array($config['components']['captcha']['options'])) {
				$options = $config['components']['captcha']['options'];
			}

			switch($config['components']['captcha']['type']) {
				case 'standard';
					$captcha = CaptchaFactory::build($options, $sessionBag);
				break;
				
				//@TODO Add more
				
				default:
					throw new \Exception('Unknown captcha adapter supplied');
			}
		}

		return $captcha;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'captcha';
	}
}

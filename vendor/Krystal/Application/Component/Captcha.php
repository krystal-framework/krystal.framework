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
use RuntimeException;

final class Captcha implements ComponentInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input)
	{
		$sessionBag = $container->get('sessionBag');

		if (isset($config['components']['captcha']['type'])) {
			// By default, no options to override
			$options = array();

			// Alter $options if we have them in user's configuration
			if (isset($config['components']['captcha']['options']) && is_array($config['components']['captcha']['options'])) {
				$options = $config['components']['captcha']['options'];
			}

			$type =& $config['components']['captcha']['type'];

			switch($type) {
				case 'standard';
					$captcha = CaptchaFactory::build($options, $sessionBag);
				break;

				default:
					throw new RuntimeException(sprintf('Unknown CAPTCHA adapter supplied "%s"', $type));
			}

			return $captcha;

		} else {

			return false;
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'captcha';
	}
}

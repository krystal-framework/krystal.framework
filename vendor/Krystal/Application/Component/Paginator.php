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

use Krystal\Paginate\Paginator as Component;
use Krystal\Paginate\Style;
use Krystal\Application\InputInterface;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;

final class Paginator implements ComponentInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input)
	{
		$options = $config['components']['paginator'];

		// By default there's no style adapter
		$style = null;

		if (isset($options['style'])) {
			switch (strtolower($options['style'])) {
				case 'digg':
					$style = new Style\DiggStyle();
				break;

				case 'slide':
					// By default SlideStyle requires more than 5 items to be activated
					$amount = 5;

					// Alter default value if specified explicitly in configuration array
					if (isset($options['options']['amount']) && is_numeric($options['options']['amount'])) {
						$amount = (int) $options['options']['amount'];
					}

					$style = new Style\SlideStyle($amount);
				break;
			}
		}

		return new Component($style);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'paginator';
	}
}

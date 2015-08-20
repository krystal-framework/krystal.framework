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

use Krystal\Validate\ValidatorFactory as Component;
use Krystal\Validate\Renderer;
use Krystal\Application\InputInterface;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;
use RuntimeException;

final class ValidatorFactory implements ComponentInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function getInstance(DependencyInjectionContainerInterface $container, array $config, InputInterface $input)
	{
		if (isset($config['components']['validator'])) {
			// Save a reference to array key
			$options =& $config['components']['validator'];

			// Translator configuration
			if (!isset($options['translate']) || $options['translate'] === false) {
				$translator = null;
			} else {
				$translator = $container->get('translator');
			}

			if (isset($options['render'])) {
				switch ($options['render']) {

					case 'JsonCollection':
						$template = isset($options['template']) ? $options['template'] : null;
						$renderer = new Renderer\JsonCollection($template);
					break;

					case 'MessagesOnly':
						$renderer = new Renderer\MessagesOnly();
					break;

					case 'Standard':
						$renderer = new Renderer\Standard();
					break;

					case 'StandardJson':
						$renderer = new Renderer\StandardJson();
					break;

					default:
						throw new RuntimeException(sprintf('Unsupported validation renderer supplied in configuration "%s"', $options['render']));
				}
			}

			return new Component($translator, $renderer);

		} else {

			// Component isn't defined in configuration
			return false;
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'validatorFactory';
	}
}

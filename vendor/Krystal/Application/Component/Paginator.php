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
		//$mapperFactory = $container->get('mapperFactory');
		//@TODO Read configuration
		$style = new Style\DiggStyle();

		$paginator = new Component($style);
		//$mapperFactory->setPaginator($paginator);

		return $paginator;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getName()
	{
		return 'paginator';
	}
}

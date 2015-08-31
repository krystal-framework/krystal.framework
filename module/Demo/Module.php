<?php

namespace Demo;

use Krystal\Application\Module\AbstractModule;

final class Module extends AbstractModule
{
	/**
	 * Returns routes of this module
	 * 
	 * @return array
	 */
	public function getRoutes()
	{
		return array(
			'/' => array(
				'controller' => 'Welcome@indexAction'
			),

			'/test' => array(
				'controller' => 'Welcome@testAction',
			)
		);
	}

	/**
	 * Returns prepared service instances of this module
	 * 
	 * @return array
	 */
	public function getServiceProviders()
	{
		return array(
		
		);
	}
}
